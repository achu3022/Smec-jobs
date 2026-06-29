<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobCategory;
use App\Models\CourseCategory;

class MappingController extends Controller
{
    public function index()
    {
        // Return job categories with their mapped course categories
        $mappings = JobCategory::with('courseCategories')->get();
        return response()->json($mappings);
    }
    
    public function getOptions()
    {
        // For the dropdowns in the frontend
        $jobCategories = JobCategory::select('id', 'name')->orderBy('name')->get();
        $courseCategories = CourseCategory::select('id', 'name')->orderBy('name')->get();
        
        return response()->json([
            'categories' => $jobCategories,
            'courses' => $courseCategories // keeping key as 'courses' for frontend simplicity, but it's categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_category_id' => 'required|exists:job_categories,id',
            'course_id' => 'required|exists:course_categories,id', // frontend still sends course_id
        ]);

        $category = JobCategory::findOrFail($request->job_category_id);
        
        // syncWithoutDetaching prevents duplicates and avoids wiping other mappings
        $category->courseCategories()->syncWithoutDetaching([$request->course_id]);

        return response()->json(['message' => 'Mapping created successfully']);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'job_category_id' => 'required|exists:job_categories,id',
            'course_id' => 'required|exists:course_categories,id',
        ]);

        $category = JobCategory::findOrFail($request->job_category_id);
        
        $category->courseCategories()->detach($request->course_id);

        return response()->json(['message' => 'Mapping removed successfully']);
    }
}
