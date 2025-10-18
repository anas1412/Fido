<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'client_name',
        'client_mf',
        'client_address',
        'invoice_number',
        'date',
        'total_hors_taxe',
        'tva',
        'montant_ttc',
        'timbre_fiscal',
        'net_a_payer',
        'mode_de_paiement',
        'mode_de_livraison',
        'banque',
        'iban',
        'swift',
        'nombre_de_lot',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
