<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Company;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with(['company', 'category'])->withTrashed()->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } else if ($request->status === 'closed') {
                $query->where('is_active', false);
            } else if ($request->status === 'featured') {
                $query->where('is_featured', true);
            }
        }

        $jobs = $query->paginate(20);
        return response()->json($jobs);
    }

    public function getOptions()
    {
        $categories = JobCategory::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        
        return response()->json([
            'categories' => $categories,
            'companies' => $companies
        ]);
    }

    public function update(Request $request, $id)
    {
        $job = Job::withTrashed()->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'job_category_id' => 'required|exists:job_categories,id',
            'company_id' => 'required|exists:companies,id',
            'job_type' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $job->update($request->all());

        return response()->json([
            'message' => 'Job updated successfully',
            'job' => $job
        ]);
    }

    public function toggleStatus($id)
    {
        $job = Job::withTrashed()->findOrFail($id);
        $job->is_active = !$job->is_active;
        $job->save();

        return response()->json(['message' => 'Job status updated']);
    }

    public function toggleFeatured($id)
    {
        $job = Job::withTrashed()->findOrFail($id);
        $job->is_featured = !$job->is_featured;
        $job->save();

        return response()->json(['message' => 'Job featured status updated']);
    }

    public function destroy($id)
    {
        $job = Job::withTrashed()->findOrFail($id);
        $job->forceDelete();

        return response()->json(['message' => 'Job permanently deleted']);
    }
}
