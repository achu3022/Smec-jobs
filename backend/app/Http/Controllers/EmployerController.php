<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationStatusUpdated;
use App\Mail\InterviewScheduled;
use Illuminate\Support\Str;

class EmployerController extends Controller
{
    public function getCompany(Request $request)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $company = Company::where('user_id', $request->user()->id)->first();
        return response()->json(['company' => $company]);
    }

    public function updateCompany(Request $request)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'industry' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'company_size' => 'nullable|string|max:255',
            'year_established' => 'nullable|string|max:4',
            'headquarters' => 'nullable|string|max:255',
            'branch_locations' => 'nullable|array',
            'contact_person' => 'nullable|string|max:255',
            'hr_contact_details' => 'nullable|array',
            'social_links' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $company = Company::where('user_id', $request->user()->id)->first();
        
        $logoPath = $company ? $company->logo : null;
        $coverPath = $company ? $company->cover_image : null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('company_logos', 'public');
        }
        
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('company_covers', 'public');
        }

        $company = Company::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'name' => $request->name,
                'description' => $request->description,
                'industry' => $request->industry,
                'location' => $request->location,
                'website' => $request->website,
                'logo' => $logoPath,
                'cover_image' => $coverPath,
                'company_size' => $request->company_size,
                'year_established' => $request->year_established,
                'headquarters' => $request->headquarters,
                'branch_locations' => $request->branch_locations,
                'contact_person' => $request->contact_person,
                'hr_contact_details' => $request->hr_contact_details,
                'social_links' => $request->social_links,
            ]
        );

        return response()->json(['message' => 'Company profile updated', 'company' => $company]);
    }

    public function myJobs(Request $request)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $company = Company::where('user_id', $request->user()->id)->first();
        if (!$company) {
            return response()->json([]);
        }

        $jobs = Job::with(['applications.user.applicantProfile', 'applications.user.applicantEducations', 'applications.user.applicantExperiences', 'applications.resume', 'category'])
            ->where('company_id', $company->id)
            ->latest()
            ->get();
        return response()->json($jobs);
    }

    public function storeJob(Request $request)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $company = Company::where('user_id', $request->user()->id)->first();
        if (!$company) {
            return response()->json(['message' => 'Please create a company profile first.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'job_type' => 'required|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'experience_level' => 'nullable|string|max:255',
            'education_level' => 'nullable|string|max:255',
            'job_category_id' => 'required|exists:job_categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $job = Job::create([
            'company_id' => $company->id,
            'job_category_id' => $request->job_category_id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'job_type' => $request->job_type,
            'experience_level' => $request->experience_level,
            'education_level' => $request->education_level,
            'is_remote' => $request->is_remote ?? false,
            'is_featured' => false,
            'is_active' => true,
        ]);

        return response()->json(['message' => 'Job posted successfully', 'job' => $job], 201);
    }
    public function getApplication(Request $request, $id)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $application = \App\Models\Application::with([
            'user.applicantProfile',
            'user.applicantEducations',
            'user.applicantExperiences',
            'resume',
            'job'
        ])->find($id);

        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        // Verify the employer owns the job
        $company = Company::where('user_id', $request->user()->id)->first();
        if (!$company || $application->job->company_id !== $company->id) {
            return response()->json(['message' => 'Unauthorized access to application'], 403);
        }

        return response()->json(['application' => $application]);
    }

    public function updateApplicationStatus(Request $request, $id)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:new,resume_reviewed,shortlisted,assessment,hr_interview,tech_interview,final_interview,offer_sent,hired,rejected,hold'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $application = \App\Models\Application::with('job.company')->findOrFail($id);
        
        // Ensure the job belongs to the employer's company
        if ($application->job->company->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }

        $application->status = $request->status;
        $application->save();

        // Trigger Notification to Applicant
        \App\Models\SystemNotification::create([
            'user_id' => $application->user_id,
            'title' => 'Application Update',
            'message' => 'The status of your application for ' . $application->job->title . ' has been updated to: ' . ucfirst(str_replace('_', ' ', $request->status)),
            'type' => 'info',
            'link' => '/applicant/applications'
        ]);

        Mail::to($application->user->email)->queue(new ApplicationStatusUpdated($application));

        return response()->json(['message' => 'Application status updated', 'application' => $application]);
    }

    public function getApplicants(Request $request)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $company = Company::where('user_id', $request->user()->id)->first();
        if (!$company) {
            return response()->json([]);
        }

        $query = \App\Models\Application::with([
            'user.applicantProfile',
            'user.applicantEducations',
            'user.applicantExperiences',
            'resume',
            'job'
        ])->whereHas('job', function($q) use ($company) {
            $q->where('company_id', $company->id);
        });

        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        return response()->json($query->latest()->get());
    }

    public function updateApplicantMeta(Request $request, $id)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $application = \App\Models\Application::with('job.company')->findOrFail($id);
        
        if ($application->job->company->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }

        if ($request->has('rating')) {
            $application->rating = $request->rating;
        }
        if ($request->has('employer_notes')) {
            $application->employer_notes = $request->employer_notes;
        }
        
        $application->save();

        return response()->json(['message' => 'Applicant details updated', 'application' => $application]);
    }

    public function toggleSavedCandidate(Request $request)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $company = Company::where('user_id', $request->user()->id)->first();
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        $existing = \App\Models\SavedCandidate::where('company_id', $company->id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['message' => 'Candidate removed from saved list', 'saved' => false]);
        } else {
            \App\Models\SavedCandidate::create([
                'company_id' => $company->id,
                'user_id' => $request->user_id,
            ]);
            return response()->json(['message' => 'Candidate saved successfully', 'saved' => true]);
        }
    }

    public function getSavedCandidates(Request $request)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $company = Company::where('user_id', $request->user()->id)->first();
        if (!$company) {
            return response()->json([]);
        }

        $saved = \App\Models\SavedCandidate::with(['user.applicantProfile', 'user.applicantEducations', 'user.applicantExperiences'])
            ->where('company_id', $company->id)
            ->latest()
            ->get();

        return response()->json($saved);
    }

    public function getInterviews(Request $request)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $company = Company::where('user_id', $request->user()->id)->first();
        if (!$company) {
            return response()->json([]);
        }

        $interviews = \App\Models\Interview::with(['application.user', 'application.job'])
            ->whereHas('application.job', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->orderBy('scheduled_at', 'asc')
            ->get();

        return response()->json($interviews);
    }

    public function scheduleInterview(Request $request, $application_id)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'scheduled_at' => 'required|date',
            'interviewer_name' => 'nullable|string|max:255',
            'location_url' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $application = \App\Models\Application::with('job.company', 'user')->findOrFail($application_id);
        
        if ($application->job->company->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }

        $interview = \App\Models\Interview::create([
            'application_id' => $application_id,
            'scheduled_at' => $request->scheduled_at,
            'interviewer_name' => $request->interviewer_name,
            'location_url' => $request->location_url,
            'notes' => $request->notes,
            'status' => 'scheduled'
        ]);

        // Trigger Notification to Applicant
        \App\Models\SystemNotification::create([
            'user_id' => $application->user_id,
            'title' => 'Interview Scheduled',
            'message' => 'You have an interview scheduled for ' . $application->job->title . ' at ' . $request->scheduled_at,
            'type' => 'info',
            'link' => '/applicant/interviews'
        ]);

        $interview->load('application.job.company', 'application.user');

        Mail::to($application->user->email)
            ->cc($request->user()->email) // Send a copy to the employer
            ->queue(new InterviewScheduled($interview));

        return response()->json(['message' => 'Interview scheduled successfully', 'interview' => $interview]);
    }

    public function getMessages(Request $request)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Just get all messages where employer is sender or receiver, grouped by the other user
        $messages = \App\Models\Message::with(['sender', 'receiver', 'application.job'])
            ->where('sender_id', $request->user()->id)
            ->orWhere('receiver_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
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
    public function updateJob(Request $request, $id)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'job_type' => 'required|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'experience_level' => 'nullable|string|max:255',
            'education_level' => 'nullable|string|max:255',
            'job_category_id' => 'required|exists:job_categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $job = Job::with('company')->findOrFail($id);
        
        if ($job->company->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }

        $job->update([
            'job_category_id' => $request->job_category_id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'job_type' => $request->job_type,
            'experience_level' => $request->experience_level,
            'education_level' => $request->education_level,
            'is_remote' => $request->is_remote ?? false,
        ]);

        return response()->json(['message' => 'Job updated successfully', 'job' => $job]);
    }

    public function toggleJobStatus(Request $request, $id)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $job = Job::with('company')->findOrFail($id);
        
        if ($job->company->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }

        $job->is_active = !$job->is_active;
        $job->save();

        return response()->json(['message' => 'Job status updated', 'is_active' => $job->is_active]);
    }

    public function toggleUrgentJob(Request $request, $id)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $job = Job::with('company')->findOrFail($id);
        
        if ($job->company->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }

        $job->is_urgent = !$job->is_urgent;
        $job->save();

        return response()->json(['message' => 'Job urgency updated', 'is_urgent' => $job->is_urgent]);
    }

    public function duplicateJob(Request $request, $id)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $job = Job::with('company')->findOrFail($id);
        
        if ($job->company->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }

        $newJob = $job->replicate();
        $newJob->title = $newJob->title . ' (Copy)';
        $newJob->is_active = false; // Duplicates should start as inactive/draft
        $newJob->is_urgent = false;
        $newJob->is_featured = false;
        $newJob->views_count = 0;
        $newJob->save();

        return response()->json(['message' => 'Job duplicated successfully', 'job' => $newJob]);
    }
    public function getDashboardStats(Request $request)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $company = Company::where('user_id', $request->user()->id)->first();
        if (!$company) {
            return response()->json([
                'active_jobs' => 0,
                'total_applications' => 0,
                'shortlisted' => 0,
                'today_interviews' => 0,
                'upcoming_interviews' => 0,
                'new_messages' => 0,
                'unread_notifications' => 0,
                'profile_completion' => 0,
                'recent_applications' => []
            ]);
        }

        $activeJobsCount = Job::where('company_id', $company->id)->where('is_active', true)->count();
        $totalApplications = \App\Models\Application::whereHas('job', function($q) use ($company) {
            $q->where('company_id', $company->id);
        })->count();

        $shortlistedCount = \App\Models\Application::whereHas('job', function($q) use ($company) {
            $q->where('company_id', $company->id);
        })->where('status', 'shortlisted')->count();

        $todayInterviews = \App\Models\Interview::whereHas('application.job', function($q) use ($company) {
            $q->where('company_id', $company->id);
        })->whereDate('scheduled_at', \Carbon\Carbon::today())->count();

        $upcomingInterviews = \App\Models\Interview::whereHas('application.job', function($q) use ($company) {
            $q->where('company_id', $company->id);
        })->where('scheduled_at', '>=', \Carbon\Carbon::now())->count();

        $unreadMessages = \App\Models\Message::where('receiver_id', $request->user()->id)
            ->whereNull('read_at')->count();
            
        $unreadNotifications = \App\Models\SystemNotification::where('user_id', $request->user()->id)
            ->where('is_read', false)->count();

        // Calculate profile completion
        $profileFields = ['name', 'industry', 'description', 'website', 'company_size', 'established_year', 'headquarters', 'logo', 'contact_person', 'contact_email', 'contact_phone'];
        $filledFields = 0;
        foreach ($profileFields as $field) {
            if (!empty($company->$field)) {
                $filledFields++;
            }
        }
        $profileCompletion = round(($filledFields / count($profileFields)) * 100);

        $recentApplications = \App\Models\Application::with(['user', 'job'])
            ->whereHas('job', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'active_jobs' => $activeJobsCount,
            'total_applications' => $totalApplications,
            'shortlisted' => $shortlistedCount,
            'today_interviews' => $todayInterviews,
            'upcoming_interviews' => $upcomingInterviews,
            'new_messages' => $unreadMessages,
            'unread_notifications' => $unreadNotifications,
            'profile_completion' => $profileCompletion,
            'recent_applications' => $recentApplications
        ]);
    }
}
