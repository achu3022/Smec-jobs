<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Interview extends Model
{
    use HasUuid;

    protected $fillable = [
        'application_id', 'scheduled_at', 'interviewer_name', 
        'location_url', 'status', 'notes'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
