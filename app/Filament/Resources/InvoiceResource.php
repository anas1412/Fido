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

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Wizard\Step::make('Client & Basic Information')
                        ->schema([
                            Forms\Components\Select::make('client_id')
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
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('client_mf')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('invoice_number')
                                ->required()
                                ->maxLength(255)
                                ->readOnly(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.name')
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
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
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
                Section::make('Client Information')
                    ->schema([
                        TextEntry::make('client.name')
                            ->label('Client Name')
                            ->weight('bold')
                            ->icon('heroicon-o-user-circle'),
                        TextEntry::make('client.mf')
                            ->label('Client Tax ID')
                            ->icon('heroicon-o-identification'),
                        TextEntry::make('client_name')
                            ->label('Client of Client Name')
                            ->weight('bold'),
                        TextEntry::make('client_mf')
                            ->label('Client of Client Tax ID'),
                    ])
                    ->columns(2),

                Section::make('Invoice Details')
                    ->schema([
                        TextEntry::make('invoice_number')
                            ->label('Invoice Number')
                            ->weight('bold'),
                        TextEntry::make('date')
                            ->label('Invoice Date')
                            ->date(),
                        TextEntry::make('status')
                            ->label('Status'),
                    ])
                    ->columns(3),

                Section::make('Invoice Items')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                TextEntry::make('object')
                                    ->label('Object'),
                                TextEntry::make('quantity')
                                    ->label('Quantity'),
                                TextEntry::make('single_price')
                                    ->label('Unit Price')
                                    ->money('tnd'),
                                TextEntry::make('total_price')
                                    ->label('Total Price')
                                    ->money('tnd'),
                            ])
                            ->columns(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Financial Details')
                    ->schema([
                        TextEntry::make('total_hors_taxe')
                            ->label('Total HT')
                            ->money('tnd'),
                        TextEntry::make('tva')
                            ->label('TVA')
                            ->money('tnd'),
                        TextEntry::make('montant_ttc')
                            ->label('Total TTC')
                            ->money('tnd')
                            ->weight('bold'),
                        TextEntry::make('timbre_fiscal')
                            ->label('Fiscal Stamp')
                            ->money('tnd'),
                        TextEntry::make('net_a_payer')
                            ->label('Net to Pay')
                            ->money('tnd')
                            ->weight('bold'),
                    ])
                    ->columns(2),
            ]);
    }
}
