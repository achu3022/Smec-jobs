<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Company;
use App\Models\Application;
use App\Models\CourseEnquiry;
use Carbon\Carbon;

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

        // 7-day registration trend
        $registrationTrend = [];
        $categories = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $categories[] = $date->format('M d');
            $count = User::whereDate('created_at', $date)->count();
            $registrationTrend[] = $count;
        }

        // Applications by status
        $applicationsByStatus = Application::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            
        // Map status to values for Donut chart
        $statusLabels = ['pending', 'reviewed', 'shortlisted', 'rejected', 'hired'];
        $statusSeries = [];
        foreach ($statusLabels as $status) {
            $statusSeries[] = $applicationsByStatus[$status] ?? 0;
        }

        // Recent Users
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        return response()->json([
            'stats' => [
                'jobSeekers' => $jobSeekers,
                'employers' => $employers,
                'activeJobs' => $activeJobs,
                'applicationsToday' => $applicationsToday,
                'newRegistrations' => $newRegistrations,
                'revenue' => '₹0', // Placeholder
                'courseEnquiries' => $courseEnquiries,
                'recentActivities' => 0, // Placeholder
            ],
            'charts' => [
                'registrations' => [
                    'series' => [['name' => 'New Users', 'data' => $registrationTrend]],
                    'categories' => $categories
                ],
                'applications' => [
                    'series' => $statusSeries,
                    'labels' => ['Pending', 'Reviewed', 'Shortlisted', 'Rejected', 'Hired']
                ]
            ],
            'recentUsers' => $recentUsers
        ]);
    }
}
