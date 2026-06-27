<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class Blog extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = ["blog_category_id", "author_id", "title", "slug", "content", "image", "published_at"];

    public function category() { return $this->belongsTo(BlogCategory::class, "blog_category_id"); }
    public function author() { return $this->belongsTo(User::class, "author_id"); }
}
