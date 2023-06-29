<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'snake_case_name',
        'description',
        'activities',
        'hours',
        'admission',
        'countryId',
        'cityId',
    ];
    protected $table = "articles";
    public function touristcPoint (): BelongsTo
    {
        return $this->belongsTo(TouristicPoint::class);
    }

    
}