<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    protected $temporary_password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $temporary_password)
    {
        $this->user = $user;

        $this->temporary_password = $temporary_password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reset_password')
            ->subject("Reset Password")
            ->with([
                'firstname' => $this->user->firstname,
                'temporary_password' => $this->temporary_password
            ]);
    }
}
