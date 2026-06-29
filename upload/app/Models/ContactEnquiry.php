<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class ContactEnquiry extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = ["name", "email", "subject", "message", "is_read"];
}
