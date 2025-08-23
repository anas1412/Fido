<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Models\TaxSetting;
use App\Models\Client;
use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Infolists;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;



class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static string | \UnitEnum | null $navigationGroup = null;

    protected static ?string $navigationLabel = null;

    public static function getNavigationGroup(): string
    {
        return __('Clients Area');
    }

    public static function getNavigationLabel(): string
    {
        return __('Invoices');
    }

    public static function getModelLabel(): string
    {
        return __('Invoice');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Invoices');
    }

    public static function canCreate(): bool
    {
        return !auth()->user()?->is_demo;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Wizard\Step::make(__('Client & Basic Information'))
                        ->schema([
                            Forms\Components\Select::make('client_id')
                                ->label(__('Client'))
                                ->relationship('client', 'name')
                                ->searchable()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state) {
                                        $currentYear = date('Y');
                                        $count = \App\Models\Invoice::count() + 1;
                                        do {
                                            $newInvoiceNumber = str_pad($count, 4, '0', STR_PAD_LEFT) . $currentYear;
                                            $count++;
                                        } while (\App\Models\Invoice::where('invoice_number', $newInvoiceNumber)->exists());

                                        $set('invoice_number', $newInvoiceNumber);
                                    }
                                }),
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
                                ->readOnly(),
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
                                ->live()
                                ->reactive(),
                        ])
                        ->afterValidation(function (callable $set, $get) {
                            $items = $get('items') ?? [];
                            $totalHorsTaxe = 0;

                            foreach ($items as $index => $item) {
                                $quantity = (float) ($item['quantity'] ?? 0);
                                $singlePrice = (float) ($item['single_price'] ?? 0);
                                $items[$index]['total_price'] = $quantity * $singlePrice;
                                $totalHorsTaxe += $quantity * $singlePrice;
                            }

                            $set('items', $items);

                            $taxSettings = TaxSetting::first();
                            $newTva = $get('exonere_tva') ? 0 : ($totalHorsTaxe * $taxSettings->tva);
                            $newMontantTTC = $totalHorsTaxe + $newTva;
                            $newTimbreFiscal = $get('exonere_tf') ? 0 : $taxSettings->tf;
                            $newNetAPayer = $newMontantTTC + $newTimbreFiscal;

                            $set('total_hors_taxe', $totalHorsTaxe);
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label(__('Invoice Number'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label(__('Client Name'))
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
            ->actions([
                ViewAction::make(),
                EditAction::make()->visible(!auth()->user()?->is_demo),
                DeleteAction::make()->visible(!auth()->user()?->is_demo),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->visible(!auth()->user()?->is_demo),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view' => Pages\ViewInvoice::route('/{record}'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('Client Information'))
                    ->schema([
                        TextEntry::make('client.name')
                            ->label(__('Client Name'))
                            ->weight('bold')
                            ->icon('heroicon-o-user-circle'),
                        TextEntry::make('client.mf')
                            ->label(__('Client Tax ID'))
                            ->icon('heroicon-o-identification'),
                        TextEntry::make('client_name')
                            ->label(__('Client of Client Name'))
                            ->weight('bold'),
                        TextEntry::make('client_mf')
                            ->label(__('Client of Client Tax ID')),
                    ])
                    ->columns(2),

                Section::make(__('Invoice Details'))
                    ->schema([
                        TextEntry::make('invoice_number')
                            ->label(__('Invoice Number'))
                            ->weight('bold'),
                        TextEntry::make('date')
                            ->label(__('Invoice Date'))
                            ->date(),
                        TextEntry::make('status')
                            ->label(__('Status')),
                    ])
                    ->columns(3),

                Section::make(__('Invoice Items'))
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                TextEntry::make('object')
                                    ->label(__('Object')),
                                TextEntry::make('quantity')
                                    ->label(__('Quantity')),
                                TextEntry::make('single_price')
                                    ->label(__('Unit Price'))
                                    ->money('tnd'),
                                TextEntry::make('total_price')
                                    ->label(__('Total Price'))
                                    ->money('tnd'),
                            ])
                            ->columns(4)
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Financial Details'))
                    ->schema([
                        TextEntry::make('total_hors_taxe')
                            ->label(__('Total HT'))
                            ->money('tnd'),
                        TextEntry::make('tva')
                            ->label(__('TVA'))
                            ->money('tnd'),
                        TextEntry::make('montant_ttc')
                            ->label(__('Total TTC'))
                            ->money('tnd')
                            ->weight('bold'),
                        TextEntry::make('timbre_fiscal')
                            ->label(__('Fiscal Stamp'))
                            ->money('tnd'),
                        TextEntry::make('net_a_payer')
                            ->label(__('Net to Pay'))
                            ->money('tnd')
                            ->weight('bold'),
                    ])
                    ->columns(2),
            ]);
    }
}
