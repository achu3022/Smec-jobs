<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseEnquiry;
use App\Models\Course;

class CourseController extends Controller
{
    public function enquire(Request $request, $courseId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'district' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);

        $course = Course::findOrFail($courseId);

        $enquiry = CourseEnquiry::create([
            'course_id' => $course->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'district' => $validated['district'],
            'state' => $validated['state'],
            'message' => $validated['message'] ?? 'Enquiry submitted from job details page.',
        ]);

        return response()->json([
            'message' => 'Enquiry submitted successfully.',
            'enquiry' => $enquiry
        ], 201);
    }
}
