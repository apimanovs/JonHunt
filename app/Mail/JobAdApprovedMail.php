<?php

namespace App\Mail;

use App\Models\JobAdvertisement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobAdApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jobAd;

    public function __construct(JobAdvertisement $jobAd)
    {
        $this->jobAd = $jobAd;
    }

    public function build()
    {
        return $this->subject('Your Job Advertisement Has Been Approved')
            ->view('emails.job_ad_approved')
            ->with([
                'jobTitle' => $this->jobAd->Title,
                'userName' => $this->jobAd->creator,
            ]);
    }
}
