<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class PostImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'imageUrl',
        'touristic_point_id'
    ];

    protected $table = "images";

    public function touristicPoint (): BelongsTo
    {
        return $this->belongsTo(TouristicPoint::class);
    }   
    public function article(): HasOne
    {
        return $this->hasOne(Article::class);
    } 
}
