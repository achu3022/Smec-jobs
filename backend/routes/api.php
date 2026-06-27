<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Job;
use App\Models\JobCategory;

use App\Http\Controllers\AuthController;

// Auth endpoints
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Email Verification Routes
Route::get('/email/verify/{id}/{hash}', function ($id, $hash, Request $request) {
    $user = \App\Models\User::findOrFail($id);
    
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return redirect(env('FRONTEND_URL', 'http://localhost:3000') . '/login?error=invalid_signature');
    }
    
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));
    }
    
    // Redirect to frontend login with verified param
    return redirect(env('FRONTEND_URL', 'http://localhost:3000') . '/login?verified=1');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/resend', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $user = \App\Models\User::where('email', $request->email)->first();
    
    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }
    
    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Already verified.']);
    }
    
    $user->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification link sent!']);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);

    // Admin Routes
    Route::middleware('can:admin')->group(function () {
        Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);
        Route::get('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'index']);
        Route::put('/admin/users/{id}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus']);
        Route::delete('/admin/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy']);
        
        Route::get('/admin/mappings', [\App\Http\Controllers\Admin\MappingController::class, 'index']);
        Route::get('/admin/mappings/options', [\App\Http\Controllers\Admin\MappingController::class, 'getOptions']);
        Route::post('/admin/mappings', [\App\Http\Controllers\Admin\MappingController::class, 'store']);
        Route::delete('/admin/mappings', [\App\Http\Controllers\Admin\MappingController::class, 'destroy']);
        
        Route::get('/admin/courses', [\App\Http\Controllers\Admin\CourseController::class, 'index']);
        Route::post('/admin/courses', [\App\Http\Controllers\Admin\CourseController::class, 'store']);
        Route::put('/admin/courses/{id}', [\App\Http\Controllers\Admin\CourseController::class, 'update']);
        Route::delete('/admin/courses/{id}', [\App\Http\Controllers\Admin\CourseController::class, 'destroy']);
        Route::get('/admin/course-categories', [\App\Http\Controllers\Admin\CourseController::class, 'getCategories']);
        
        Route::get('/admin/companies', [\App\Http\Controllers\Admin\CompanyController::class, 'index']);
        Route::put('/admin/companies/{id}', [\App\Http\Controllers\Admin\CompanyController::class, 'update']);
        Route::put('/admin/companies/{id}/toggle-status', [\App\Http\Controllers\Admin\CompanyController::class, 'toggleStatus']);
        Route::delete('/admin/companies/{id}', [\App\Http\Controllers\Admin\CompanyController::class, 'destroy']);
        
        Route::get('/admin/jobs', [\App\Http\Controllers\Admin\JobController::class, 'index']);
        Route::get('/admin/jobs/options', [\App\Http\Controllers\Admin\JobController::class, 'getOptions']);
        Route::put('/admin/jobs/{id}', [\App\Http\Controllers\Admin\JobController::class, 'update']);
        Route::put('/admin/jobs/{id}/toggle-status', [\App\Http\Controllers\Admin\JobController::class, 'toggleStatus']);
        Route::put('/admin/jobs/{id}/toggle-featured', [\App\Http\Controllers\Admin\JobController::class, 'toggleFeatured']);
        Route::delete('/admin/jobs/{id}', [\App\Http\Controllers\Admin\JobController::class, 'destroy']);
        
        Route::get('/admin/export/{type}', [\App\Http\Controllers\Admin\ReportController::class, 'export']);
        
        Route::get('/admin/enquiries', [\App\Http\Controllers\Admin\EnquiryController::class, 'index']);
        Route::put('/admin/enquiries/{id}/status', [\App\Http\Controllers\Admin\EnquiryController::class, 'updateStatus']);
        Route::delete('/admin/enquiries/{id}', [\App\Http\Controllers\Admin\EnquiryController::class, 'destroy']);
    });

    // Applicant Routes
    Route::post('/applicant/resume', [\App\Http\Controllers\ApplicantController::class, 'uploadResume']);
    Route::get('/applicant/applications', [\App\Http\Controllers\ApplicantController::class, 'myApplications']);
    Route::get('/applicant/profile', [\App\Http\Controllers\ApplicantController::class, 'getProfile']);
    Route::post('/applicant/profile', [\App\Http\Controllers\ApplicantController::class, 'updateProfile']);
    Route::post('/applicant/education', [\App\Http\Controllers\ApplicantController::class, 'storeEducation']);
    Route::delete('/applicant/education/{id}', [\App\Http\Controllers\ApplicantController::class, 'deleteEducation']);
    Route::post('/applicant/experience', [\App\Http\Controllers\ApplicantController::class, 'storeExperience']);
    Route::delete('/applicant/experience/{id}', [\App\Http\Controllers\ApplicantController::class, 'deleteExperience']);
    Route::post('/applicant/saved-jobs/{id}', [\App\Http\Controllers\ApplicantController::class, 'toggleSavedJob']);
    Route::get('/applicant/saved-jobs', [\App\Http\Controllers\ApplicantController::class, 'getSavedJobs']);
    Route::post('/jobs/{job}/apply', [\App\Http\Controllers\ApplicantController::class, 'apply']);

    // Employer Profile & Dashboard
    Route::get('/employer/dashboard-stats', [\App\Http\Controllers\EmployerController::class, 'getDashboardStats']);
    Route::get('/employer/company', [\App\Http\Controllers\EmployerController::class, 'getCompany']);
    Route::post('/employer/company', [\App\Http\Controllers\EmployerController::class, 'updateCompany']);
    Route::get('/employer/jobs', [\App\Http\Controllers\EmployerController::class, 'myJobs']);
    Route::post('/employer/jobs', [\App\Http\Controllers\EmployerController::class, 'storeJob']);
    Route::put('/employer/jobs/{id}', [\App\Http\Controllers\EmployerController::class, 'updateJob']);
    Route::put('/employer/jobs/{id}/toggle-status', [\App\Http\Controllers\EmployerController::class, 'toggleJobStatus']);
    Route::put('/employer/jobs/{id}/toggle-urgent', [\App\Http\Controllers\EmployerController::class, 'toggleUrgentJob']);
    Route::post('/employer/jobs/{id}/duplicate', [\App\Http\Controllers\EmployerController::class, 'duplicateJob']);
    Route::get('/employer/applications', [\App\Http\Controllers\EmployerController::class, 'getApplicants']);
    Route::get('/employer/applications/{id}', [\App\Http\Controllers\EmployerController::class, 'getApplication']);
    Route::put('/employer/applications/{id}/status', [\App\Http\Controllers\EmployerController::class, 'updateApplicationStatus']);
    Route::put('/employer/applications/{id}/meta', [\App\Http\Controllers\EmployerController::class, 'updateApplicantMeta']);
    Route::post('/employer/applications/{id}/interviews', [\App\Http\Controllers\EmployerController::class, 'scheduleInterview']);
    Route::get('/employer/candidates/saved', [\App\Http\Controllers\EmployerController::class, 'getSavedCandidates']);
    Route::post('/employer/candidates/saved', [\App\Http\Controllers\EmployerController::class, 'toggleSavedCandidate']);
    Route::get('/employer/interviews', [\App\Http\Controllers\EmployerController::class, 'getInterviews']);
    Route::get('/employer/messages', [\App\Http\Controllers\EmployerController::class, 'getMessages']);
    Route::post('/employer/messages', [\App\Http\Controllers\EmployerController::class, 'sendMessage']);
});

// Public endpoints
Route::get('/jobs/search', [\App\Http\Controllers\JobSearchController::class, 'search']);
Route::get('/jobs/{uuid}', [App\Http\Controllers\JobController::class, 'show']);
Route::post('/courses/{id}/enquire', [\App\Http\Controllers\PublicCourseController::class, 'enquire']);
Route::get('/jobs/featured', function () {
    return Job::with('company')->where('is_featured', true)->latest()->take(6)->get();
});

Route::get('/categories', function () {
    return JobCategory::withCount('jobs')->get();
});

Route::get('/companies', function () {
    return \App\Models\Company::orderBy('name')->get();
});

Route::get('/courses', function () {
    return \App\Models\Course::with('category')->orderBy('title')->get();
});
