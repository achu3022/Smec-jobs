<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class Faq extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = ["question", "answer", "category"];
}
