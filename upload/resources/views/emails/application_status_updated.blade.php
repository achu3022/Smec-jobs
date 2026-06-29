<!DOCTYPE html>
<html>
<head>
    <title>Application Status Updated</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Hello {{ $application->user->name }},</h2>
    
    <p>There has been an update regarding your application for the position of <strong>{{ $application->job->title }}</strong> at <strong>{{ $application->job->company->name }}</strong>.</p>
    
    <p>Your current application status is now: <strong>{{ ucfirst(str_replace('_', ' ', $application->status)) }}</strong>.</p>
    
    <p>Log in to your SMEC Jobs dashboard to view more details.</p>
    
    <p>Best Regards,<br>SMEC Jobs Team</p>
</body>
</html>
