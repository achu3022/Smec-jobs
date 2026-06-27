<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('category')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('course_code', 'like', "%{$search}%");
            });
        }

        $courses = $query->paginate(20);
        return response()->json($courses);
    }

    public function getCategories()
    {
        return response()->json(CourseCategory::orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'course_category_id' => 'required|exists:course_categories,id',
            'course_code' => 'required|string|max:50|unique:courses,course_code',
            'provider' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:100',
            'fee_1' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $course = Course::create($request->all());

        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'course_category_id' => 'required|exists:course_categories,id',
            'course_code' => 'required|string|max:50|unique:courses,course_code,'.$course->id,
            'provider' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:100',
            'fee_1' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $course->update($request->all());

        return response()->json([
            'message' => 'Course updated successfully',
            'course' => $course
        ]);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json(['message' => 'Course deleted successfully']);
    }
}
