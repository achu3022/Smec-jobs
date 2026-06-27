<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\JobCategory;
use App\Models\Job;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployerSeeder extends Seeder
{
    public function run(): void
    {
        // Employer 1
        $employer1 = User::firstOrCreate(
            ['email' => 'tech.employer@smecjobs.com'],
            [
                'name' => 'Tech Corp HR',
                'password' => Hash::make('password'),
                'role' => 'employer',
            ]
        );

        $company1 = Company::firstOrCreate(
            ['user_id' => $employer1->id],
            [
                'name' => 'Tech Solutions Ltd',
                'description' => 'A leading software and hardware solutions provider in India.',
                'industry' => 'Technology',
                'location' => 'Bangalore, India'
            ]
        );

        // Employer 2
        $employer2 = User::firstOrCreate(
            ['email' => 'manufacturing.employer@smecjobs.com'],
            [
                'name' => 'Industrial Manufacturing HR',
                'password' => Hash::make('password'),
                'role' => 'employer',
            ]
        );

        $company2 = Company::firstOrCreate(
            ['user_id' => $employer2->id],
            [
                'name' => 'Global Manufacturing Corp',
                'description' => 'Innovators in industrial automation and heavy machinery.',
                'industry' => 'Manufacturing',
                'location' => 'Pune, India'
            ]
        );

        // Seed some jobs for these employers
        $softwareCategory = JobCategory::firstOrCreate(['slug' => 'software-engineering'], ['name' => 'Software Engineering']);
        $automationCategory = JobCategory::firstOrCreate(['slug' => 'automation'], ['name' => 'Automation']);

        Job::firstOrCreate(
            [
                'company_id' => $company1->id,
                'title' => 'Fullstack React Developer'
            ],
            [
                'job_category_id' => $softwareCategory->id,
                'description' => 'We are looking for a mid-level React and Node.js developer to join our growing tech team.',
                'location' => 'Bangalore, India',
                'salary_min' => 60000,
                'salary_max' => 95000,
                'job_type' => 'Full-time',
                'is_featured' => false,
                'is_remote' => true,
            ]
        );

        Job::firstOrCreate(
            [
                'company_id' => $company2->id,
                'title' => 'PLC Programmer'
            ],
            [
                'job_category_id' => $automationCategory->id,
                'description' => 'Seeking an experienced PLC Programmer (Siemens, Allen Bradley) for our new manufacturing plant.',
                'location' => 'Pune, India',
                'salary_min' => 40000,
                'salary_max' => 70000,
                'job_type' => 'Full-time',
                'is_featured' => true,
                'is_remote' => false,
            ]
        );

        $this->command->info('Employer data and sample jobs seeded successfully.');
        $this->command->info('Login 1: tech.employer@smecjobs.com / password');
        $this->command->info('Login 2: manufacturing.employer@smecjobs.com / password');
    }
}
