<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseEnquiry;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class PublicCourseController extends Controller
{
    public function enquire(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        // If authenticated via Sanctum
        if (Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();
            
            // Prevent duplicate entries
            if (CourseEnquiry::where('course_id', $course->id)->where('user_id', $user->id)->exists()) {
                return response()->json([
                    'message' => 'You have already submitted an enquiry for this course.'
                ], 400);
            }
            
            $profile = $user->applicantProfile;
            
            $enquiry = CourseEnquiry::create([
                'course_id' => $course->id,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $profile->mobile ?? $request->input('phone', ''),
                'district' => $profile->district ?? null,
                'state' => $profile->state ?? null,
                'message' => 'Automatic enquiry from registered user.',
                'status' => 'new'
            ]);
        } else {
            // Guest enquiry
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'message' => 'nullable|string'
            ]);

            $enquiry = CourseEnquiry::create([
                'course_id' => $course->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->input('message', ''),
                'status' => 'new'
            ]);
        }

        return response()->json([
            'message' => 'Enquiry submitted successfully',
            'enquiry' => $enquiry
        ], 201);
    }
}
