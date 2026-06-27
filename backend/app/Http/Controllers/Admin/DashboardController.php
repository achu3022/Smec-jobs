<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Company;
use App\Models\Application;
use App\Models\CourseEnquiry;

class DashboardController extends Controller
{
    public function index()
    {
        $jobSeekers = User::where('role', 'applicant')->count();
        $employers = User::where('role', 'employer')->count();
        $activeJobs = Job::where('is_active', true)->count();
        $applicationsToday = Application::whereDate('created_at', today())->count();
        $newRegistrations = User::whereDate('created_at', today())->count();
        $courseEnquiries = CourseEnquiry::count();

        return response()->json([
            'jobSeekers' => $jobSeekers,
            'employers' => $employers,
            'activeJobs' => $activeJobs,
            'applicationsToday' => $applicationsToday,
            'newRegistrations' => $newRegistrations,
            'revenue' => '₹0', // Placeholder
            'courseEnquiries' => $courseEnquiries,
            'recentActivities' => 0, // Placeholder
        ]);
    }
}
