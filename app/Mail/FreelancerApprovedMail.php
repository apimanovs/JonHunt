<?php

namespace App\Mail;

use App\Models\Freelancer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FreelancerApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $freelancer;

    public function __construct(Freelancer $freelancer)
    {
        $this->freelancer = $freelancer;
    }

    public function build()
    {
        return $this->subject('Your Freelancer Profile Has Been Approved')
            ->view('emails.freelancer_approved')
            ->with([
                'userName' => $this->freelancer->user->name,
            ]);
    }
}

