<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'job_application_id',
        'project_application_id',
        'client_id',
        'freelancer_id',
        'status',
        'result_text',
        'result_file',
        'cancel_reason',
    ];
    

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function projectApplication()
    {
        return $this->belongsTo(ProjectApplication::class);
    }
    
}
