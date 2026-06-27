<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class Course extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = [
        'course_category_id', 'title', 'description', 'provider', 'url', 'duration', 'price',
        'course_code', 'fee_1', 'fee_2', 'fee_3', 'qualifications', 'placement_assistance'
    ];

    public function category() { return $this->belongsTo(CourseCategory::class, "course_category_id"); }
    public function jobCategories() { return $this->belongsToMany(JobCategory::class, "job_course_mappings"); }
}
