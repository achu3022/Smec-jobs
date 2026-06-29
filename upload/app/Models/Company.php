<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class Company extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = [
        "user_id", "name", "logo", "description", "website", "industry", "location",
        "cover_image", "company_size", "year_established", "headquarters", "branch_locations",
        "contact_person", "hr_contact_details", "is_verified", "verification_documents", "social_links"
    ];

    protected $casts = [
        'branch_locations' => 'array',
        'hr_contact_details' => 'array',
        'verification_documents' => 'array',
        'social_links' => 'array',
        'is_verified' => 'boolean'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function jobs() { return $this->hasMany(Job::class); }
}
