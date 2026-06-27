<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\JobCategory;
use App\Models\Job;
use Illuminate\Support\Str;

class AdditionalJobsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'admin@smecjobs.com')->first();
        if (!$user) {
            $this->command->error("Admin user not found. Run DatabaseSeeder first.");
            return;
        }

        $company = Company::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => 'Tech Innovations Ltd',
                'description' => 'Leading the future of automation and systems.',
                'industry' => 'Engineering',
                'location' => 'Bangalore, India'
            ]
        );

        $categories = [
            'Automation' => JobCategory::firstOrCreate(['slug' => 'automation'], ['name' => 'Automation']),
            'Embedded Systems' => JobCategory::firstOrCreate(['slug' => 'embedded-systems'], ['name' => 'Embedded Systems']),
            'Data Science' => JobCategory::firstOrCreate(['slug' => 'data-science'], ['name' => 'Data Science']),
            'Networking' => JobCategory::firstOrCreate(['slug' => 'networking'], ['name' => 'Networking']),
        ];

        // Seed Automation Job
        Job::create([
            'company_id' => $company->id,
            'job_category_id' => $categories['Automation']->id,
            'title' => 'Industrial Automation Engineer',
            'description' => 'We are seeking an experienced Industrial Automation Engineer to design, program, and commission PLC, SCADA, and DCS systems.',
            'location' => 'Mumbai, Maharashtra',
            'salary_min' => 45000,
            'salary_max' => 75000,
            'job_type' => 'Full-time',
            'is_featured' => true,
        ]);

        // Seed Embedded Job
        Job::create([
            'company_id' => $company->id,
            'job_category_id' => $categories['Embedded Systems']->id,
            'title' => 'Embedded Firmware Developer',
            'description' => 'Looking for a firmware developer with strong C/C++ skills for microcontrollers (ARM, PIC) and IoT integration.',
            'location' => 'Bangalore, Karnataka',
            'salary_min' => 50000,
            'salary_max' => 90000,
            'job_type' => 'Full-time',
            'is_featured' => true,
        ]);

        // Seed Data Science Job
        Job::create([
            'company_id' => $company->id,
            'job_category_id' => $categories['Data Science']->id,
            'title' => 'Data Analytics Consultant',
            'description' => 'Join our AI team to build advanced Machine Learning models and leverage Big Data pipelines.',
            'location' => 'Remote',
            'salary_min' => 80000,
            'salary_max' => 120000,
            'job_type' => 'Contract',
            'is_featured' => true,
            'is_remote' => true,
        ]);

        // Seed Networking Job
        Job::create([
            'company_id' => $company->id,
            'job_category_id' => $categories['Networking']->id,
            'title' => 'AWS Cloud Architect & Linux Admin',
            'description' => 'Seeking a Cloud Architect with deep expertise in AWS, Linux system administration, and cyber security protocols.',
            'location' => 'Pune, Maharashtra',
            'salary_min' => 70000,
            'salary_max' => 110000,
            'job_type' => 'Full-time',
            'is_featured' => false,
        ]);

        $this->command->info('Successfully seeded new technology jobs (Automation, Embedded, Data Science, Networking).');
    }
}
