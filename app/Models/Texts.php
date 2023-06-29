<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Texts extends Model
{
    use HasFactory;
    protected $fillable = [
            'banner',
            'home',
            'about',
            'destination',
            'tour',
            'city',
            'country',
            'travel',
            'languageId',
    ];
}
