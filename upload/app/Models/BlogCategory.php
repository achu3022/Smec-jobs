<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class BlogCategory extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = ["name", "slug"];

    public function blogs() { return $this->hasMany(Blog::class); }
}
