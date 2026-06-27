<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class CourseCategory extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = ["name", "slug", "description"];

    public function courses() { return $this->hasMany(Course::class); }
}
