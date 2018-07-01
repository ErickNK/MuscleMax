<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    private $inviteCode;

    /**
     * Create a new message instance.
     *
     * @param $inviteCode
     */
    public function __construct($inviteCode)
    {
        $this->inviteCode = $inviteCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("timespaceSupport@gmail.com")
            ->view('email.invitationEmail')
            ->with('inviteCode',$this->inviteCode);
    }
}
