<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewScheduled extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $interview;

    public function __construct($interview)
    {
        $this->interview = $interview;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Interview Scheduled: ' . $this->interview->application->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.interview_scheduled',
        );
    }
}
