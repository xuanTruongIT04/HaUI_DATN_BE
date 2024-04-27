<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $verifiUrl;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $verifiUrl)
    {
        $this->email = $email;
        $this->verifiUrl = $verifiUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verification Account')->view('emails.verificationAccount');
    }
}