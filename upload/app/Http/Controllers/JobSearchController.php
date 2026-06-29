<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = Job::with('company')->where('is_active', true);

        if ($request->filled('q')) {
            $query->whereFullText(['title', 'location'], $request->q);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('type')) {
            $query->where('job_type', $request->type);
        }

        $jobs = $query->latest()->paginate(12);

        if ($user = $request->user('sanctum')) {
            $appliedJobIds = \App\Models\Application::where('user_id', $user->id)
                ->pluck('job_id')
                ->toArray();
            $savedJobIds = $user->savedJobs()->pluck('jobs.id')->toArray();
            
            $jobs->getCollection()->transform(function ($job) use ($appliedJobIds, $savedJobIds) {
                $job->has_applied = in_array($job->id, $appliedJobIds);
                $job->is_saved = in_array($job->id, $savedJobIds);
                return $job;
            });
        } else {
            $jobs->getCollection()->transform(function ($job) {
                $job->has_applied = false;
                $job->is_saved = false;
                return $job;
            });
        }

        return response()->json($jobs);
    }
}
