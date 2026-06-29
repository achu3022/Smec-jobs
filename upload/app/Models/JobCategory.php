<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class JobCategory extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = ["name", "slug", "description", "icon"];

    public function jobs() { return $this->hasMany(Job::class); }
    public function courses() { return $this->belongsToMany(Course::class, "job_course_mappings"); }
    public function courseCategories() { return $this->belongsToMany(CourseCategory::class, "course_category_job_category"); }
}
