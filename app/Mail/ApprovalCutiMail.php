<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ApprovalCutiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $greeting;
    public $user;
    public $notif;
    public $url;
    /**
     * Create a new message instance.
     *
     * @param $greeting
     * @param $user
     * @param $notif
     * @param $url
     */
    public function __construct( $greeting, $user, $notif, $url )
    {
        $this->greeting = $greeting;
        $this->user = $user;
        $this->notif = $notif;
        $this->url = $url;
    }
        /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('News: Approval Status Cuti')
            ->view('emails.approvalStatus')
            ->with([
                'feature' => 'Cuti',
                'greeting' => $this->greeting,
                'user' => $this->user,
                'notif' => $this->notif,
                'url' => $this->url,
            ]);
    }
}
