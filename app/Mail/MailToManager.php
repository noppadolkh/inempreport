<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailToManager extends Mailable
{
    use Queueable, SerializesModels;
    public $empcometoday;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($empcometoday)
    {
        $this->empcometoday = $empcometoday['empcometoday'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.dailyarrive');
    }
}
