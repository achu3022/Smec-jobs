<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Message extends Model
{
    use HasUuid;

    protected $fillable = [
        'sender_id', 'receiver_id', 'application_id', 'content', 'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
