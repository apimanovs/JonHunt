<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_ad_id',
        'user_id',
        'requirements',
    ];

    public function jobAd()
    {
        return $this->belongsTo(JobAdvertisement::class, 'job_ad_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
