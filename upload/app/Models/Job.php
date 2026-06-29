<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class Job extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = ["company_id", "job_category_id", "title", "description", "location", "salary_min", "salary_max", "job_type", "experience_level", "education_level", "is_remote", "is_featured", "is_active", "is_urgent", "views_count"];

    public function company() { return $this->belongsTo(Company::class); }
    public function category() { return $this->belongsTo(JobCategory::class, "job_category_id"); }
    public function skills() { return $this->belongsToMany(Skill::class, "job_skills"); }
    public function applications() { return $this->hasMany(Application::class); }
    public function savedBy() { return $this->belongsToMany(User::class, "saved_jobs"); }
}
