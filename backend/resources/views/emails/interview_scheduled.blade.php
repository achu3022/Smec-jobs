<!DOCTYPE html>
<html>
<head>
    <title>Interview Scheduled</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Hello {{ $interview->application->user->name }},</h2>
    
    <p>Great news! You have been invited to an interview for the position of <strong>{{ $interview->application->job->title }}</strong> at <strong>{{ $interview->application->job->company->name }}</strong>.</p>
    
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin: 20px 0;">
        <h3 style="margin-top: 0; color: #4f46e5;">Interview Details</h3>
        <p><strong>Date & Time:</strong> {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('F j, Y, g:i a') }}</p>
        <p><strong>Location / Link:</strong> 
            @if(filter_var($interview->location_url, FILTER_VALIDATE_URL))
                <a href="{{ $interview->location_url }}">{{ $interview->location_url }}</a>
            @else
                {{ $interview->location_url ?? 'TBD' }}
            @endif
        </p>
        @if($interview->notes)
            <p><strong>Notes from Employer:</strong><br>{{ $interview->notes }}</p>
        @endif
    </div>
    
    <p>Please log in to your dashboard if you need to review the job posting.</p>
    
    <p>Best of luck!<br>SMEC Jobs Team</p>
</body>
</html>
