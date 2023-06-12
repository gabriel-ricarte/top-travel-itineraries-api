<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TouristicPoint extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'snake_case_name',
        'description',
        'espacial_description',
        'latitude',
        'longitude',
        'cityId'
    ];

    protected $table = "touristic_points";

    public function postImages(): HasMany
    {
        return $this->hasMany(PostImage::class);
    }  
}
