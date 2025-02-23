<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectApplication extends Model
{
    protected $fillable = [
        'project_id',
        'freelancer_id',
        'cover_letter',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }
}

