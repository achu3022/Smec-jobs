<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Skill extends Model
{
    use HasUuid;
    protected $fillable = ["name", "slug"];

    public function jobs() { return $this->belongsToMany(Job::class, "job_skills"); }
    public function resumes() { return $this->belongsToMany(Resume::class, "resume_skills"); }
}
