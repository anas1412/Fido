<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Honoraire extends Model
{
    use HasFactory;

    protected $fillable = [
        'note',
        'object',
        'montantHT',
        'montantTTC',
        'tva',
        'rs',
        'tf',
        'netapyer',
        'client_id'
    ];
}
