<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions\CreateAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Models\TaxSetting;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;

class InvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Invoices');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Invoices');
    }

    public static function getModelLabel(): string
    {
        return __('Invoice');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Wizard\Step::make(__('Client & Basic Information'))
                        ->schema([
                            Forms\Components\TextInput::make('client_name')
                                ->label(__('Client Name'))
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('client_mf')
                                ->label(__('Client Tax ID'))
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('invoice_number')
                                ->label(__('Invoice Number'))
                                ->required()
                                ->maxLength(255)
                                ->readOnly()
                                ->default(function () {
                                    $currentYear = date('Y');
                                    $count = Invoice::count() + 1;
                                    do {
                                        $newInvoiceNumber = str_pad($count, 4, '0', STR_PAD_LEFT) . $currentYear;
                                        $count++;
                                    } while (Invoice::where('invoice_number', $newInvoiceNumber)->exists());
                                    return $newInvoiceNumber;
                                }),
                            Forms\Components\DatePicker::make('date')
                                ->label(__('Date'))
                                ->required()
                                ->default(now()->toDateString()),
                        ]),
                    Wizard\Step::make(__('Invoice Items'))
                        ->schema([
                            Forms\Components\Repeater::make('items')
                                ->label(__('Invoice Items'))
                                ->relationship()
                                ->schema([
                                    Forms\Components\TextInput::make('object')
                                        ->label(__('Object'))
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpanFull(),
                                    Grid::make(3)
                                        ->schema([
                                            Forms\Components\TextInput::make('quantity')
                                                ->label(__('Quantity'))
                                                ->required()
                                                ->numeric()
                                                ->live()
                                                ->afterStateUpdated(function ($state, callable $set, $get) {
                                                    $quantity = (float) $state;
                                                    $singlePrice = (float) $get('single_price');
                                                    $set('total_price', $quantity * $singlePrice);
                                                }),
                                            Forms\Components\TextInput::make('single_price')
                                                ->label(__('Unit Price'))
                                                ->required()
                                                ->numeric()
                                                ->live()
                                                ->afterStateUpdated(function ($state, callable $set, $get) {
                                                    $singlePrice = (float) $state;
                                                    $quantity = (float) $get('quantity');
                                                    $set('total_price', $singlePrice * $quantity);
                                                }),
                                            Forms\Components\TextInput::make('total_price')
                                                ->label(__('Total Price'))
                                                ->required()
                                                ->numeric()
                                                ->readOnly()
                                                ->live(),
                                        ]),
                                ])
                                ->columnSpan('full')
                                ->live() // Make the repeater live to trigger updates on total_hors_taxe
                                ->reactive()
                        ])
                        ->afterValidation(function (callable $set, callable $get) {
                            $totalHorsTaxe = 0;
                            foreach ($get('items') as $item) {
                                $totalHorsTaxe += (float) $item['total_price'];
                            }
                            $set('total_hors_taxe', $totalHorsTaxe);

                            $taxSettings = TaxSetting::first();
                            $newTva = $get('exonere_tva') ? 0 : ($totalHorsTaxe * $taxSettings->tva);
                            $newMontantTTC = $totalHorsTaxe + $newTva;
                            $newTimbreFiscal = $get('exonere_tf') ? 0 : $taxSettings->tf;
                            $newNetAPayer = $newMontantTTC + $newTimbreFiscal;

                            $set('tva', $newTva);
                            $set('montant_ttc', $newMontantTTC);
                            $set('timbre_fiscal', $newTimbreFiscal);
                            $set('net_a_payer', $newNetAPayer);
                        }),
                    Wizard\Step::make(__('Financial Details & Status'))
                        ->schema([
                            Forms\Components\TextInput::make('total_hors_taxe')
                                ->label(__('Total HT'))
                                ->required()
                                ->numeric()
                                ->readOnly(), // Changed from disabled()
                                
                            Forms\Components\TextInput::make('tva')
                                ->label(__('TVA'))
                                ->required()
                                ->numeric()
                                ->readOnly(), // Changed from disabled()
                                
                            Forms\Components\TextInput::make('montant_ttc')
                                ->label(__('Total TTC'))
                                ->required()
                                ->numeric()
                                ->readOnly(), // Changed from disabled()
                                
                            Forms\Components\TextInput::make('timbre_fiscal')
                                ->label(__('Fiscal Stamp'))
                                ->required()
                                ->numeric()
                                ->readOnly(), // Changed from disabled()
                                
                            Forms\Components\TextInput::make('net_a_payer')
                                ->label(__('Net to Pay'))
                                ->required()
                                ->numeric()
                                ->readOnly(), // Changed from disabled()
                                
                            Forms\Components\Select::make('status')
                                ->options([
                                    'draft' => __('Draft'),
                                    'sent' => __('Sent'),
                                    'paid' => __('Paid'),
                                    'overdue' => __('Overdue'),
                                ])
                                ->required(),
                            Forms\Components\Toggle::make('exonere_tva')
                                ->label(__('Exonération TVA'))
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, $get) {
                                    $taxSettings = TaxSetting::first();
                                    $totalHorsTaxe = $get('total_hors_taxe');
                                    $newTva = $state ? 0 : ($totalHorsTaxe * $taxSettings->tva);
                                    $newMontantTTC = $totalHorsTaxe + $newTva;
                                    $newTimbreFiscal = $get('exonere_tf') ? 0 : $taxSettings->tf;
                                    $newNetAPayer = $newMontantTTC + $newTimbreFiscal;

                                    $set('tva', $newTva);
                                    $set('montant_ttc', $newMontantTTC);
                                    $set('net_a_payer', $newNetAPayer);
                                }),
                            Forms\Components\Toggle::make('exonere_tf')
                                ->label(__('Exonération Timbre Fiscal'))
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, $get) {
                                    $taxSettings = TaxSetting::first();
                                    $newTimbreFiscal = $state ? 0 : $taxSettings->tf;
                                    $newNetAPayer = $get('montant_ttc') + $newTimbreFiscal;

                                    $set('timbre_fiscal', $newTimbreFiscal);
                                    $set('net_a_payer', $newNetAPayer);
                                }),
                        ]),
                ])->columnSpanFull(),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('invoice_number')
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label(__('Invoice Number'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label(__('Date'))
                    ->date(),
                Tables\Columns\TextColumn::make('net_a_payer')
                    ->label(__('Net to Pay'))
                    ->money('tnd'),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->visible(!auth()->user()?->is_demo),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()->visible(!auth()->user()?->is_demo),
                    DeleteAction::make()->visible(!auth()->user()?->is_demo),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(!auth()->user()?->is_demo),
                ]),
            ]);
    }
}
