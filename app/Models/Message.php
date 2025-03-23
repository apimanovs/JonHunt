<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $primaryKey = 'MessageID';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = [
        'SenderID',
        'ReceiverID',
        'Content',
        'order_id',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'SenderID');
    }
    
    public function receiver()
    {
        return $this->belongsTo(User::class, 'ReceiverID');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
