<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_name',
        'address',
        'city',
        'phone',
        'mf'
    ];

    public function honoraires(): HasMany
    {
        return $this->hasMany(Honoraire::class);
    }

    public function noteDeDebits(): HasMany
    {
        return $this->hasMany(NoteDeDebit::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function honorairesWithRS(): HasMany
    {
        return $this->hasMany(Honoraire::class)->whereNotNull('rs')->where('rs', '>', 0);
    }
}
