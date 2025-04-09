<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'project_id', 'job_advertisement_id', 'reason'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function project() {
        return $this->belongsTo(Project::class);
    }
    
    public function jobAdvertisement() {
        return $this->belongsTo(JobAdvertisement::class);
    }
    
}
