<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class Application extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = ["job_id", "user_id", "resume_id", "cover_letter", "status", "resume_score", "rating", "employer_notes"];

    public function job() { return $this->belongsTo(Job::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function resume() { return $this->belongsTo(Resume::class); }
    public function interviews() { return $this->hasMany(Interview::class); }
    public function messages() { return $this->hasMany(Message::class); }
}
