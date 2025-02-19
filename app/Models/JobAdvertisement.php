<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAdvertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'creator', 
        'examples',
        'price',
        'creator_id',
    ];

    // Полиморфное отношение отзывов для job ad
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function avatar()
    {
        return $this->hasOne(Avatar::class, 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function portfolios()
    {
        return $this->hasMany(JobAdvertisementPortfolio::class, 'job_ad_id');
    }
}
