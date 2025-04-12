<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function build()
    {
        return $this->subject('Your Project Has Been Approved')
            ->view('emails.project_approved')
            ->with([
                'projectTitle' => $this->project->title,
                'userName' => $this->project->creator,
            ]);
    }
}
