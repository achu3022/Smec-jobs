<!DOCTYPE html>
<html>
<head>
    <title>New Application Received</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Hello {{ $application->job->company->name }},</h2>
    
    <p>You have received a new application for the position of <strong>{{ $application->job->title }}</strong>.</p>
    
    <p><strong>Applicant Name:</strong> {{ $application->user->name }}</p>
    <p><strong>Applicant Email:</strong> {{ $application->user->email }}</p>
    
    <p>Please log in to your dashboard to review their full profile and resume.</p>
    
    <p>Best Regards,<br>SMEC Jobs Team</p>
</body>
</html>
