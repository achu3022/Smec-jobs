<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function show(Request $request, $uuid)
    {
        $job = Job::with(['company', 'category'])->where('uuid', $uuid)->firstOrFail();

        $job->has_applied = false;
        $job->is_saved = false;
        if ($user = $request->user('sanctum')) {
            $job->has_applied = \App\Models\Application::where('user_id', $user->id)
                ->where('job_id', $job->id)
                ->exists();
            $job->is_saved = $user->savedJobs()->where('job_id', $job->id)->exists();
        }

        $relatedCourses = collect();

        // 1. Primary Match: Try to match Job Category with Course Category
        if ($job->category) {
            $jobCatName = $job->category->name; // e.g., "Automation"
            
            // Try exact or partial match on category name
            $courseCategory = CourseCategory::where('name', 'like', "%{$jobCatName}%")->first();
            
            if ($courseCategory) {
                // Fetch ALL courses for this matching category as requested
                $relatedCourses = Course::where('course_category_id', $courseCategory->id)->get();
            }
        }

        // 2. Secondary Match: Strict Title Matching (only if category didn't match)
        if ($relatedCourses->isEmpty()) {
            // Remove generic words that cause false positives
            $genericWords = ['engineer', 'developer', 'manager', 'consultant', 'senior', 'junior', 'professional', 'industrial'];
            $cleanTitle = strtolower(preg_replace('/[^A-Za-z0-9 ]/', '', $job->title));
            $keywords = array_diff(explode(' ', $cleanTitle), $genericWords);
            
            $query = Course::query();
            $hasKeywords = false;
            
            foreach($keywords as $word) {
                if (strlen($word) > 3) {
                    $hasKeywords = true;
                    // Only match course title, not category, to avoid broad category pulls
                    $query->orWhere('title', 'like', "%{$word}%");
                }
            }
            
            if ($hasKeywords) {
                $relatedCourses = $query->take(5)->get();
            }
        }

        // 3. Ultimate fallback if still empty
        if ($relatedCourses->isEmpty()) {
            $relatedCourses = Course::inRandomOrder()->take(4)->get();
        }

        return response()->json([
            'job' => $job,
            'related_courses' => $relatedCourses
        ]);
    }
}
