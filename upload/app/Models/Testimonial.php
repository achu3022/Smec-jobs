<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class Testimonial extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = ["name", "role", "content", "avatar", "is_approved"];
}
