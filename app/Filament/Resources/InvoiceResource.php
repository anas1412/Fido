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
use Filament\Infolists;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Actions\ActionGroup;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;



class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 40;

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
                                            $newInvoiceNumber = str_pad($count, 2, '0', STR_PAD_LEFT) . $currentYear;
                                            $count++;
                                        } while (\App\Models\Invoice::where('invoice_number', $newInvoiceNumber)->exists());

                                        $set('invoice_number', $newInvoiceNumber);

                                        // Also set nombre_de_lot here
                                        $invoiceNumber = $newInvoiceNumber;
                                        $nombreDeLot = 'NTF' . str_replace('-', '', $invoiceNumber);
                                        $set('nombre_de_lot', $nombreDeLot);
                                    }
                                }),
                            Forms\Components\TextInput::make('invoice_number')
                                ->label(__('Invoice Number'))
                                ->required()
                                ->maxLength(255)
                                ->readOnly()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state) {
                                        $invoiceNumber = $state;
                                        $nombreDeLot = 'NTF' . str_replace('-', '', $invoiceNumber);
                                        $set('nombre_de_lot', $nombreDeLot);
                                    }
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
                                                            ->columnSpanFull()
                                                            ->live(onBlur: true),
                                                        Grid::make(3)
                                                            ->schema([
                                                                Forms\Components\TextInput::make('quantity')
                                                                    ->label(__('Quantité ou nombre de colis/paloxe'))
                                                                    ->required()
                                                                    ->numeric()
                                                                    ->live(onBlur: true)
                                                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                                                        $quantity = (float) $state;
                                                                        $singlePrice = (float) $get('single_price');
                                                                        $set('total_price', $quantity * $singlePrice);
                                                                    }),
                                                                Forms\Components\TextInput::make('single_price')
                                                                    ->label(__('Unit Price'))
                                                                    ->required()
                                                                    ->numeric()
                                                                    ->live(onBlur: true)
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
                                                                Forms\Components\TextInput::make('commercial_details.poids_brut_kg')
                                                                    ->label(__('Poids Brut Kg'))
                                                                    ->numeric()
                                                                    ->live(onBlur: true)
                                                                    ->default(0),
                                                                Forms\Components\TextInput::make('commercial_details.poids_net_kg')
                                                                    ->label(__('Poids Net Kg'))
                                                                    ->numeric()
                                                                    ->live(onBlur: true)
                                                                    ->default(0),
                                                            ]),
                                                    ])
                                                    ->columnSpan('full')
                                                    ->live()
                                                    ->reactive()
                                                    ->afterStateUpdated(function (callable $set, $get) {
                                                        $items = $get('items') ?? [];
                                                        $totalHorsTaxe = 0;
                    
                                                        foreach ($items as $item) {
                                                            $quantity = (float) ($item['quantity'] ?? 0);
                                                            $singlePrice = (float) ($item['single_price'] ?? 0);
                                                            $totalHorsTaxe += $quantity * $singlePrice;
                                                        }
                    
                                                        $taxSettings = TaxSetting::first();
                                                        if (!$taxSettings) {
                                                            return;
                                                        }
                    
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
                                            ]),

                                        Wizard\Step::make(__('Financial Details'))
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
                                                    
                                                
                                                Forms\Components\Toggle::make('exonere_tva')
                                                    ->label(__('Exonération TVA'))
                                                    ->live()
                                                    ->default(true)
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
                                                    ->default(true)
                                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                                        $taxSettings = TaxSetting::first();
                                                        $newTimbreFiscal = $state ? 0 : $taxSettings->tf;
                                                        $newNetAPayer = $get('montant_ttc') + $newTimbreFiscal;

                                                        $set('timbre_fiscal', $newTimbreFiscal);
                                                        $set('net_a_payer', $newNetAPayer);
                                                    }),
                                            ]),
                    
                                        Wizard\Step::make(__('Invoice Details'))
                                            ->schema([
                                                Forms\Components\TextInput::make('mode_de_paiement')
                                                    ->label(__('Mode de paiement'))
                                                    ->default('Virement bancaire, 60 jours après livraison')
                                                    ->maxLength(255)
                                                    ->live(onBlur: true),
                                                Forms\Components\TextInput::make('mode_de_livraison')
                                                    ->label(__('Mode de livraison'))
                                                    ->default('EX-WORK')
                                                    ->maxLength(255)
                                                    ->live(onBlur: true),
                                                Forms\Components\TextInput::make('banque')
                                                    ->label(__('Banque'))
                                                    ->default('STB BANK Hammamet, 340 Avenue des Nations Unies Hammamet 8050')
                                                    ->maxLength(255)
                                                    ->live(onBlur: true),
                                                Forms\Components\TextInput::make('iban')
                                                    ->label(__('IBAN'))
                                                    ->default('TN59 1030 1029 1553 6637 8885')
                                                    ->maxLength(255)
                                                    ->live(onBlur: true),
                                                Forms\Components\TextInput::make('swift')
                                                    ->label(__('SWIFT'))
                                                    ->default('STBKTNTT')
                                                    ->maxLength(255)
                                                    ->live(onBlur: true),
                                                Forms\Components\TextInput::make('nombre_de_lot')
                                                    ->label(__('Nombre de lot'))
                                                    ->maxLength(255)
                                                    ->readOnly()
                                                    ->live(onBlur: true)
                                                    ->afterStateHydrated(function (callable $set, callable $get) {
                                                        $invoiceNumber = $get('invoice_number');
                                                        if ($invoiceNumber) {
                                                            $nombreDeLot = 'NTF' . str_replace('-', '', $invoiceNumber);
                                                            $set('nombre_de_lot', $nombreDeLot);
                                                        }
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
                    ->money(\App\Models\CompanySetting::first()?->currency ?? 'TND'),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                Action::make('print')
                    ->label(__('Print'))
                    ->color('info')
                    ->icon('heroicon-o-printer')
                    ->url(fn(Invoice $record) => route('pdf.invoice', $record))
                    ->openUrlInNewTab(),

                EditAction::make()->visible(!auth()->user()?->is_demo),
                DeleteAction::make()->visible(!auth()->user()?->is_demo),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(!auth()->user()?->is_demo),
                ]),
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
                Section::make(__('Invoice Details'))
                    ->schema([
                        TextEntry::make('client.name')
                            ->label(__('Client Name'))
                            ->weight('bold')
                            ->icon('heroicon-o-user-circle'),
                        TextEntry::make('client.mf')
                            ->label(__('Client Tax ID'))
                            ->icon('heroicon-o-identification'),
                        TextEntry::make('invoice_number')
                            ->label(__('Invoice Number'))
                            ->weight('bold'),
                        TextEntry::make('date')
                            ->label(__('Invoice Date'))
                            ->date(),
                        TextEntry::make('mode_de_paiement')
                            ->label(__('Mode de paiement')),
                        TextEntry::make('mode_de_livraison')
                            ->label(__('Mode de livraison')),
                        TextEntry::make('banque')
                            ->label(__('Banque')),
                        TextEntry::make('iban')
                            ->label(__('IBAN')),
                        TextEntry::make('swift')
                            ->label(__('SWIFT')),
                        TextEntry::make('nombre_de_lot')
                            ->label(__('Nombre de lot')),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make(__('Financial Details'))
                    ->schema([
                        TextEntry::make('total_hors_taxe')
                            ->label(__('Total HT'))
                            ->money(\App\Models\CompanySetting::first()?->currency ?? 'TND'),
                        TextEntry::make('tva')
                            ->label(__('TVA'))
                            ->money(\App\Models\CompanySetting::first()?->currency ?? 'TND'),
                        TextEntry::make('montant_ttc')
                            ->label(__('Total TTC'))
                            ->money(\App\Models\CompanySetting::first()?->currency ?? 'TND')
                            ->weight('bold'),
                        TextEntry::make('timbre_fiscal')
                            ->label(__('Fiscal Stamp'))
                            ->money(\App\Models\CompanySetting::first()?->currency ?? 'TND'),
                        TextEntry::make('net_a_payer')
                            ->label(__('Net to Pay'))
                            ->money(\App\Models\CompanySetting::first()?->currency ?? 'TND')
                            ->weight('bold'),
                    ])->columns(5)->columnSpanFull(),

                Section::make(__('Invoice Items'))
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                TextEntry::make('object')
                                    ->label(__('Object')),
                                TextEntry::make('quantity')
                                    ->label(__('Quantité ou nombre de colis/paloxe')),
                                TextEntry::make('single_price')
                                    ->label(__('Unit Price'))
                                    ->money(\App\Models\CompanySetting::first()?->currency ?? 'TND'),
                                TextEntry::make('total_price')
                                    ->label(__('Total Price'))
                                    ->money(\App\Models\CompanySetting::first()?->currency ?? 'TND'),
                                TextEntry::make('commercial_details.poids_brut_kg')
                                    ->label(__('Poids Brut Kg')),
                                TextEntry::make('commercial_details.poids_net_kg')
                                    ->label(__('Poids Net Kg')),
                            ])
                            ->columns(3)
                    ])->columnSpanFull(),

                

            ]);
    }
}
