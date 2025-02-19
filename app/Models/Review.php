<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'ReviewID';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'reviewable_id',
        'reviewable_type',
        'UserID',
        'ReviewedUserID',
        'Rating',
        'ReviewText',
    ];

    // Полиморфное отношение – отзыв принадлежит сущности (проекту или объявлению о работе)
    public function reviewable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }

    public function reviewedUser()
    {
        return $this->belongsTo(User::class, 'ReviewedUserID');
    }
}
