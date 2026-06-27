<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class Resume extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = ["user_id", "file_path", "is_default"];

    public function user() { return $this->belongsTo(User::class); }
    public function skills() { return $this->belongsToMany(Skill::class, "resume_skills"); }
    public function applications() { return $this->hasMany(Application::class); }
}
