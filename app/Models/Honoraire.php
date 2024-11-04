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
        'date',
        'montantHT',
        'montantTTC',
        'tva',
        'rs',
        'tf',
        'netapayer',
        'client_id',
        'exonere_tf',
        'exonere_rs',
        'exonere_tva',
    ];

    protected $casts = [
        'exonere_tf' => 'boolean',
        'exonere_rs' => 'boolean',
        'exonere_tva' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    protected static function booted()
    {
        static::creating(function ($honoraire) {
            $currentYear = date('Y');
            $count = Honoraire::count() + 1; // Start from count + 1 for the new note

            // Generate a new unique note reference
            do {
                $newNote = str_pad($count, 4, '0', STR_PAD_LEFT) . $currentYear;
                $count++; // Increment for the next check if it already exists
            } while (Honoraire::where('note', $newNote)->exists()); // Check for uniqueness

            /* $newObject = "Assistance comptable de l'année $currentYear";
            $newDate = now()->toDateString(); */

            /* $honoraire->date = $newDate; */
            $honoraire->note = (string) $newNote;
            /* $honoraire->object = $newObject; */
        });
    }
}
