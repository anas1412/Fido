<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'slogan',
        'mf_number',
        'location',
        'address_line1',
        'address_line2',
        'phone1',
        'phone2',
        'phone3',
        'fax',
        'email',
    ];
}
