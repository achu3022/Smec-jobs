<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Company;

class ReportController extends Controller
{
    public function export(Request $request, $type)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"export_{$type}_" . date('Y-m-d') . ".csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($type, $request) {
            $file = fopen('php://output', 'w');
            
            if ($type === 'users') {
                fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Status', 'Registered At']);
                User::withTrashed()->chunk(100, function ($users) use ($file) {
                    foreach ($users as $user) {
                        fputcsv($file, [
                            $user->id,
                            $user->name,
                            $user->email,
                            $user->role,
                            $user->trashed() ? 'Suspended' : 'Active',
                            $user->created_at->format('Y-m-d H:i:s')
                        ]);
                    }
                });
            } elseif ($type === 'jobs') {
                fputcsv($file, ['ID', 'Title', 'Company', 'Category', 'Location', 'Type', 'Status', 'Featured', 'Created At']);
                Job::with(['company', 'category'])->withTrashed()->chunk(100, function ($jobs) use ($file) {
                    foreach ($jobs as $job) {
                        fputcsv($file, [
                            $job->id,
                            $job->title,
                            $job->company->name ?? 'N/A',
                            $job->category->name ?? 'N/A',
                            $job->location,
                            $job->job_type,
                            $job->is_active ? 'Active' : 'Closed',
                            $job->is_featured ? 'Yes' : 'No',
                            $job->created_at->format('Y-m-d H:i:s')
                        ]);
                    }
                });
            } elseif ($type === 'companies') {
                fputcsv($file, ['ID', 'Company Name', 'Industry', 'Location', 'Owner', 'Status', 'Registered At']);
                Company::with('user')->withTrashed()->chunk(100, function ($companies) use ($file) {
                    foreach ($companies as $company) {
                        fputcsv($file, [
                            $company->id,
                            $company->name,
                            $company->industry,
                            $company->location,
                            $company->user->name ?? 'N/A',
                            $company->trashed() ? 'Suspended' : 'Active',
                            $company->created_at->format('Y-m-d H:i:s')
                        ]);
                    }
                });
            } elseif ($type === 'enquiries') {
                fputcsv($file, ['ID', 'Course', 'Name', 'Email', 'Phone', 'District', 'State', 'Message', 'Status', 'Registered User', 'Date']);
                
                $query = \App\Models\CourseEnquiry::with(['course', 'user']);
                
                // Status Filter
                if ($request->filled('status') && $request->status !== 'all') {
                    $query->where('status', $request->status);
                }

                // Date Filter
                if ($request->filled('date_filter')) {
                    $now = now();
                    switch ($request->date_filter) {
                        case 'day':
                            $query->whereDate('created_at', $now->toDateString());
                            break;
                        case 'week':
                            $query->whereBetween('created_at', [$now->startOfWeek()->toDateTimeString(), $now->endOfWeek()->toDateTimeString()]);
                            break;
                        case 'month':
                            $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
                            break;
                        case 'year':
                            $query->whereYear('created_at', $now->year);
                            break;
                        case 'custom':
                            if ($request->filled('start_date') && $request->filled('end_date')) {
                                $query->whereBetween('created_at', [
                                    $request->start_date . ' 00:00:00',
                                    $request->end_date . ' 23:59:59'
                                ]);
                            }
                            break;
                    }
                }

                $query->chunk(100, function ($enquiries) use ($file) {
                    foreach ($enquiries as $enq) {
                        fputcsv($file, [
                            $enq->id,
                            $enq->course->title ?? 'N/A',
                            $enq->name,
                            $enq->email,
                            $enq->phone,
                            $enq->district,
                            $enq->state,
                            $enq->message,
                            ucfirst($enq->status),
                            $enq->user_id ? 'Yes' : 'No',
                            $enq->created_at->format('Y-m-d H:i:s')
                        ]);
                    }
                });
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
