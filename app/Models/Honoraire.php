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

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    protected static function booted()
    {
        static::creating(function ($honoraire) {
            $currentYear = date('Y');
            $count = Honoraire::where('client_id', $honoraire->client_id)->count();
            $newNote = str_pad($count + 1, 4, '0', STR_PAD_LEFT) . $currentYear;
            $newObject = "Assistance comptable de l'année $currentYear";
            $honoraire->note = $newNote;
            $honoraire->object = $newObject;
        });
    }
}
