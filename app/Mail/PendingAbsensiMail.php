<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PendingAbsensiMail extends Mailable
    implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;   // MUST be public to be accessible in the Blade view
    public $type;   // MUST be public
    public $notif;  // MUST be public
    public $url;    // MUST be public
    public $greeting; // MUST be public

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $type
     * @param $notif
     * @param $url
     * @param $greeting
     */
    public function __construct($user, $type, $notif, $url, $greeting)
    {
        $this->user = $user;   // Set user details
        $this->type = $type;   // Set notification type
        $this->notif = $notif; // Set the notification/content
        $this->url = $url;     // Set the action URL
        $this->greeting = $greeting; // Store greeting
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Reminder: Pending Absensi Email') // Email Subject
            ->view('emails.pendingAbsensi') // Blade view for email content
            ->with([
                'user' => $this->user,
                'type' => $this->type,
                'notif' => $this->notif,
                'url' => $this->url,
                'greeting' => $this->greeting
            ]); // Pass data to the Blade view
    }
}
