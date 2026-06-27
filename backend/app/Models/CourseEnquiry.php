<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class CourseEnquiry extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = ["course_id", "user_id", "name", "email", "phone", "district", "state", "message", "status", "is_read"];

    public function course() { return $this->belongsTo(Course::class); }
    public function user() { return $this->belongsTo(User::class); }
}
