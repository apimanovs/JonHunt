<?php

namespace App\Mail;

use App\Models\ProjectApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectApplicationReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(ProjectApplication $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->subject('New Application for Your Project')
            ->view('emails.project_application_received')
            ->with([
                'projectTitle' => $this->application->project->title,
                'freelancerName' => $this->application->freelancer->name,
            ]);
    }
}
