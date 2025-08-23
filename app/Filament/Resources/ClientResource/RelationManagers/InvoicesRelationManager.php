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

class InvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Wizard\Step::make('Client & Basic Information')
                        ->schema([
                            Forms\Components\TextInput::make('client_name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('client_mf')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('invoice_number')
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
                                ->required()
                                ->default(now()->toDateString()),
                        ]),
                    Wizard\Step::make('Invoice Items')
                        ->schema([
                            Forms\Components\Repeater::make('items')
                                ->relationship()
                                ->schema([
                                    Forms\Components\TextInput::make('object')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpanFull(),
                                    Grid::make(3)
                                        ->schema([
                                            Forms\Components\TextInput::make('quantity')
                                                ->required()
                                                ->numeric()
                                                ->live()
                                                ->afterStateUpdated(function ($state, callable $set, $get) {
                                                    $quantity = (float) $state;
                                                    $singlePrice = (float) $get('single_price');
                                                    $set('total_price', $quantity * $singlePrice);
                                                }),
                                            Forms\Components\TextInput::make('single_price')
                                                ->required()
                                                ->numeric()
                                                ->live()
                                                ->afterStateUpdated(function ($state, callable $set, $get) {
                                                    $singlePrice = (float) $state;
                                                    $quantity = (float) $get('quantity');
                                                    $set('total_price', $singlePrice * $quantity);
                                                }),
                                            Forms\Components\TextInput::make('total_price')
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
                    Wizard\Step::make('Financial Details & Status')
                        ->schema([
                            Forms\Components\TextInput::make('total_hors_taxe')
                                ->required()
                                ->numeric()
                                ->readOnly(), // Changed from disabled()
                                
                            Forms\Components\TextInput::make('tva')
                                ->required()
                                ->numeric()
                                ->readOnly(), // Changed from disabled()
                                
                            Forms\Components\TextInput::make('montant_ttc')
                                ->required()
                                ->numeric()
                                ->readOnly(), // Changed from disabled()
                                
                            Forms\Components\TextInput::make('timbre_fiscal')
                                ->required()
                                ->numeric()
                                ->readOnly(), // Changed from disabled()
                                
                            Forms\Components\TextInput::make('net_a_payer')
                                ->required()
                                ->numeric()
                                ->readOnly(), // Changed from disabled()
                                
                            Forms\Components\Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'sent' => 'Sent',
                                    'paid' => 'Paid',
                                    'overdue' => 'Overdue',
                                ])
                                ->required(),
                            Forms\Components\Toggle::make('exonere_tva')
                                ->label('Exonération TVA')
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
                                ->label('Exonération Timbre Fiscal')
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date(),
                Tables\Columns\TextColumn::make('net_a_payer')
                    ->money('tnd'),
                Tables\Columns\TextColumn::make('status'),
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
