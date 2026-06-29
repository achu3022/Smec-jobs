<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\JobCategory;
use App\Models\Job;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
        ]);

        $user = User::where('email', 'admin@smecjobs.com')->first();

        $company = Company::create([
            'user_id' => $user->id,
            'name' => 'Google Inc.',
            'description' => 'A global technology leader.',
            'industry' => 'Technology',
            'location' => 'San Francisco, CA',
        ]);

        $category = JobCategory::create([
            'name' => 'Software Engineering',
            'slug' => 'software-engineering',
        ]);

        Job::create([
            'company_id' => $company->id,
            'job_category_id' => $category->id,
            'title' => 'Senior Frontend Developer',
            'description' => 'Looking for an experienced Vue.js developer.',
            'location' => 'San Francisco, CA',
            'salary_min' => 120000,
            'salary_max' => 150000,
            'job_type' => 'Full-time',
            'is_featured' => true,
        ]);
        
        Job::create([
            'company_id' => $company->id,
            'job_category_id' => $category->id,
            'title' => 'Backend Laravel Engineer',
            'description' => 'Build robust APIs using Laravel.',
            'location' => 'Remote',
            'salary_min' => 100000,
            'salary_max' => 130000,
            'job_type' => 'Full-time',
            'is_featured' => true,
            'is_remote' => true,
        ]);
    }
}
