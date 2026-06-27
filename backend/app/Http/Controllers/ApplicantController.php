<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewApplicationReceived;
use Illuminate\Support\Facades\Validator;

class ApplicantController extends Controller
{
    public function uploadResume(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('resume');
        $path = $file->store('resumes', 'public');

        $resume = Resume::create([
            'user_id' => $request->user()->id,
            'file_path' => $path,
            'is_default' => true,
        ]);

        return response()->json(['message' => 'Resume uploaded successfully', 'resume' => $resume], 201);
    }

    public function myApplications(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $applications = Application::with(['job.company', 'interviews'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($applications);
    }

    public function apply(Request $request, $jobId)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $resume = Resume::where('user_id', $request->user()->id)->where('is_default', true)->first();

        if (!$resume) {
            return response()->json(['message' => 'Please upload a resume first.'], 400);
        }

        $existing = Application::where('user_id', $request->user()->id)->where('job_id', $jobId)->first();
        if ($existing) {
            return response()->json(['message' => 'Already applied for this job.'], 400);
        }

        $application = Application::create([
            'user_id' => $request->user()->id,
            'job_id' => $jobId,
            'resume_id' => $resume->id,
            'status' => 'Pending'
        ]);

        $application->load(['job.company.user']);
        $employerUser = $application->job->company->user;
        
        if ($employerUser) {
            Mail::to($employerUser->email)->queue(new NewApplicationReceived($application));
        }

        return response()->json(['message' => 'Applied successfully', 'application' => $application], 201);
    }
    public function getProfile(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $profile = \App\Models\ApplicantProfile::where('user_id', $request->user()->id)->first();
        $educations = \App\Models\ApplicantEducation::where('user_id', $request->user()->id)->get();
        $experiences = \App\Models\ApplicantExperience::where('user_id', $request->user()->id)->get();

        return response()->json([
            'profile' => $profile,
            'educations' => $educations,
            'experiences' => $experiences
        ]);
    }

    public function updateProfile(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->except(['photo']);

        $profile = \App\Models\ApplicantProfile::where('user_id', $request->user()->id)->first();
        $photoPath = $profile ? $profile->photo : null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('applicant_photos', 'public');
            $data['photo'] = $photoPath;
        }

        $profile = \App\Models\ApplicantProfile::updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        return response()->json(['message' => 'Profile updated successfully', 'profile' => $profile]);
    }

    public function storeEducation(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $education = \App\Models\ApplicantEducation::create(array_merge(
            $request->all(),
            ['user_id' => $request->user()->id]
        ));

        return response()->json(['message' => 'Education added', 'education' => $education], 201);
    }

    public function deleteEducation(Request $request, $id)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $education = \App\Models\ApplicantEducation::where('user_id', $request->user()->id)->findOrFail($id);
        $education->delete();

        return response()->json(['message' => 'Education deleted']);
    }

    public function storeExperience(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $experience = \App\Models\ApplicantExperience::create(array_merge(
            $request->all(),
            ['user_id' => $request->user()->id]
        ));

        return response()->json(['message' => 'Experience added', 'experience' => $experience], 201);
    }

    public function deleteExperience(Request $request, $id)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $experience = \App\Models\ApplicantExperience::where('user_id', $request->user()->id)->findOrFail($id);
        $experience->delete();

        return response()->json(['message' => 'Experience deleted']);
    }

    public function toggleSavedJob(Request $request, $id)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = $request->user();
        $job = \App\Models\Job::findOrFail($id);

        if ($user->savedJobs()->where('job_id', $job->id)->exists()) {
            $user->savedJobs()->detach($job->id);
            return response()->json(['message' => 'Job removed from saved jobs', 'status' => 'unsaved']);
        } else {
            $user->savedJobs()->attach($job->id);
            return response()->json(['message' => 'Job saved successfully', 'status' => 'saved']);
        }
    }

    public function getSavedJobs(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $savedJobs = $request->user()->savedJobs()->with(['company', 'category'])->get();
        return response()->json(['saved_jobs' => $savedJobs]);
    }
}
