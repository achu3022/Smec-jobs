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
            'resume' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
            'document_type' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('resume');
        $path = $file->store('resumes', 'public');
        $docType = $request->document_type ?? 'Resume';

        $resume = Resume::create([
            'user_id' => $request->user()->id,
            'file_path' => $path,
            'document_type' => $docType,
            'is_default' => $docType === 'Resume' ? true : false,
        ]);

        // Only allow one default Resume. If this is a Resume, unset others.
        if ($docType === 'Resume') {
            Resume::where('user_id', $request->user()->id)
                ->where('id', '!=', $resume->id)
                ->update(['is_default' => false]);
        }

        return response()->json(['message' => 'Document uploaded successfully', 'resume' => $resume], 201);
    }

    public function getResume(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $resume = Resume::where('user_id', $request->user()->id)->where('is_default', true)->latest()->first();
        if ($resume) {
            $resume->file_url = asset('storage/' . $resume->file_path);
        }

        return response()->json(['resume' => $resume]);
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
            'status' => 'new'
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

        if ($profile && $profile->photo) {
            $profile->photo_url = asset('storage/' . $profile->photo);
        }

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

        $data = $request->except(['photo', 'photo_url', 'id', 'user_id', 'created_at', 'updated_at']);

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

        if ($profile->photo) {
            $profile->photo_url = asset('storage/' . $profile->photo);
        }

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

    public function getRecommendedJobs(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $alerts = \App\Models\JobAlert::where('user_id', $request->user()->id)->get();
        
        if ($alerts->isEmpty()) {
            return response()->json(['jobs' => []]);
        }

        $query = \App\Models\Job::query()->with(['company', 'category'])->where('is_active', true);

        $query->where(function($q) use ($alerts) {
            foreach ($alerts as $alert) {
                $q->orWhere(function($subQ) use ($alert) {
                    if ($alert->keyword) {
                        $subQ->where(function($kQ) use ($alert) {
                            $kQ->where('title', 'like', '%' . $alert->keyword . '%')
                               ->orWhere('description', 'like', '%' . $alert->keyword . '%');
                        });
                    }
                    if ($alert->location) {
                        $subQ->where('location', 'like', '%' . $alert->location . '%');
                    }
                    if ($alert->job_type) {
                        $subQ->where('employment_type', $alert->job_type);
                    }
                    if ($alert->industry) {
                        $subQ->whereHas('category', function($catQ) use ($alert) {
                            $catQ->where('name', 'like', '%' . $alert->industry . '%');
                        });
                    }
                });
            }
        });

        // If the user created an empty alert, it would match everything.
        // The above query handles that correctly (empty alert = no constraints inside orWhere, so it returns all).
        
        $jobs = $query->latest()->take(20)->get();

        return response()->json(['jobs' => $jobs]);
    }

    public function getInterviews(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $interviews = \App\Models\Interview::whereHas('application', function($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->with(['application.job.company'])->orderBy('scheduled_at', 'asc')->get();

        return response()->json(['interviews' => $interviews]);
    }

    public function getJobAlerts(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $alerts = \App\Models\JobAlert::where('user_id', $request->user()->id)->get();
        return response()->json(['alerts' => $alerts]);
    }

    public function storeJobAlert(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'keyword' => 'nullable|string',
            'location' => 'nullable|string',
            'job_type' => 'nullable|string',
            'industry' => 'nullable|string'
        ]);

        $alert = \App\Models\JobAlert::create([
            'user_id' => $request->user()->id,
            'keyword' => $validated['keyword'] ?? null,
            'location' => $validated['location'] ?? null,
            'job_type' => $validated['job_type'] ?? null,
            'industry' => $validated['industry'] ?? null,
            'email_alerts_active' => true,
        ]);

        return response()->json(['message' => 'Job alert created', 'alert' => $alert], 201);
    }

    public function toggleJobAlert(Request $request, $id)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $alert = \App\Models\JobAlert::where('user_id', $request->user()->id)->findOrFail($id);
        $alert->email_alerts_active = !$alert->email_alerts_active;
        $alert->save();

        return response()->json(['message' => 'Job alert updated', 'alert' => $alert]);
    }

    public function deleteJobAlert(Request $request, $id)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $alert = \App\Models\JobAlert::where('user_id', $request->user()->id)->findOrFail($id);
        $alert->delete();

        return response()->json(['message' => 'Job alert deleted']);
    }

    public function getDashboardStats(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $userId = $request->user()->id;

        $totalApplied = \App\Models\Application::where('user_id', $userId)->count();
        
        $interviews = \App\Models\Interview::whereHas('application', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('status', 'scheduled')
          ->where('scheduled_at', '>=', now())
          ->count();

        // Calculate profile completion percentage (mock logic based on profile and resume)
        $profile = \App\Models\ApplicantProfile::where('user_id', $userId)->first();
        $resume = \App\Models\Resume::where('user_id', $userId)->where('is_default', true)->first();
        
        $completion = 0;
        if ($profile) {
            $completion += 40; // Base profile
            if ($profile->photo) $completion += 10;
            if ($profile->skills) $completion += 10;
        }
        if ($resume) {
            $completion += 40;
        }

        $unreadMessages = \App\Models\Message::where('receiver_id', $userId)
            ->whereNull('read_at')
            ->count();

        $unreadNotifications = \App\Models\SystemNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'total_applied' => $totalApplied,
            'upcoming_interviews' => $interviews,
            'profile_completion' => $completion,
            'unread_messages' => $unreadMessages,
            'unread_notifications' => $unreadNotifications
        ]);
    }

    public function getMessages(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = \App\Models\Message::with(['sender', 'receiver', 'application.job'])
            ->where('sender_id', $request->user()->id)
            ->orWhere('receiver_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
            'application_id' => 'nullable|exists:applications,id',
            'content' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $message = \App\Models\Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $request->receiver_id,
            'application_id' => $request->application_id,
            'content' => $request->content
        ]);

        return response()->json(['message' => 'Message sent successfully', 'data' => $message]);
    }

    public function markAsRead(Request $request, $senderId)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        \App\Models\Message::where('receiver_id', $request->user()->id)
            ->where('sender_id', $senderId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Messages marked as read']);
    }

    public function getNotifications(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $userId = $request->user()->id;

        // Automatically delete notifications older than 30 days
        \App\Models\SystemNotification::where('user_id', $userId)
            ->where('created_at', '<', now()->subDays(30))
            ->delete();

        // Check if there are unread notifications
        $unreadCount = \App\Models\SystemNotification::where('user_id', $userId)->where('is_read', false)->count();

        if ($unreadCount > 0) {
            // Only show unread notifications
            $notifications = \App\Models\SystemNotification::where('user_id', $userId)
                ->where('is_read', false)
                ->latest()
                ->paginate(15);
        } else {
            // If all read, show 10 recent
            $notifications = \App\Models\SystemNotification::where('user_id', $userId)
                ->latest()
                ->paginate(10);
        }

        return response()->json($notifications);
    }

    public function markAllNotificationsRead(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        \App\Models\SystemNotification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function markNotificationRead(Request $request, $id)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification = \App\Models\SystemNotification::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if ($notification && !$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function getDocuments(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $resumes = \App\Models\Resume::where('user_id', $request->user()->id)->latest()->get();
        foreach ($resumes as $resume) {
            $resume->file_url = asset('storage/' . $resume->file_path);
        }

        return response()->json($resumes);
    }

    public function deleteDocument(Request $request, $id)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $resume = \App\Models\Resume::where('user_id', $request->user()->id)->findOrFail($id);
        
        // Delete from storage
        \Illuminate\Support\Facades\Storage::disk('public')->delete($resume->file_path);
        $resume->delete();

        // If it was default, make the newest one default
        if ($resume->is_default) {
            $newDefault = \App\Models\Resume::where('user_id', $request->user()->id)->latest()->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        return response()->json(['message' => 'Document deleted']);
    }

    public function makeDocumentDefault(Request $request, $id)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Remove default from all others
        \App\Models\Resume::where('user_id', $request->user()->id)->update(['is_default' => false]);
        
        // Set new default
        $resume = \App\Models\Resume::where('user_id', $request->user()->id)->findOrFail($id);
        $resume->update(['is_default' => true]);

        return response()->json(['message' => 'Document set as default', 'resume' => $resume]);
    }
}
