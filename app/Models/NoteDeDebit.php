<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteDeDebit extends Model
{
    use HasFactory;

    protected $fillable = [
        'note',
        'client_id',
        'amount',
        'description',
        'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    protected static function booted()
    {
        static::creating(function ($noteDeDebit) {
            $currentYear = now()->year;
            $count = NoteDeDebit::count() + 1;

            $exists = false;
            do {
                $newNote = str_pad($count, 4, '0', STR_PAD_LEFT) . $currentYear;
                $exists = NoteDeDebit::where('note', $newNote)->exists();
                $count++;
            } while ($exists);

            $noteDeDebit->note = (string) $newNote;
        });
    }
}
