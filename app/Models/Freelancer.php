<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freelancer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'country',
        'bio',
        'specialization',
        'experience_from',
        'experience_to',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'freelancer_skill', 'freelancer_id', 'skill_id')
            ->select('skills.id', 'skills.name');
    }

    public function portfolioPhotos()
    {
        return $this->hasMany(PortfolioPhoto::class);
    }

}
