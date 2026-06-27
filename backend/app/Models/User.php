<?php
namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

use Laravel\Sanctum\HasApiTokens;

use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasUuid;

    protected $fillable = ["name", "email", "password", "role"];
    protected $hidden = ["password", "remember_token"];
    protected function casts(): array { return ["email_verified_at" => "datetime", "password" => "hashed"]; }

    public function companies() { return $this->hasMany(Company::class); }
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function applicantProfile()
    {
        return $this->hasOne(ApplicantProfile::class);
    }

    public function applicantEducations()
    {
        return $this->hasMany(ApplicantEducation::class);
    }

    public function applicantExperiences()
    {
        return $this->hasMany(ApplicantExperience::class);
    }
    public function resumes() { return $this->hasMany(Resume::class); }
    public function savedJobs() { return $this->belongsToMany(Job::class, "saved_jobs"); }
    public function blogs() { return $this->hasMany(Blog::class, "author_id"); }
    public function hasRole(string $role): bool { return $this->role === $role; }
}
