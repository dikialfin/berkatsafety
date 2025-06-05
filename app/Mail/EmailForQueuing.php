<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailForQueuing extends Mailable
{
    use Queueable, SerializesModels;
    protected $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fromEmail = isset($this->details['address'])?$this->details['address'] : env('MAIL_FROM_ADDRESS');
        $fromName = isset($this->details['name'])?$this->details['name'] : env('MAIL_FROM_NAME');

        return $this->from($fromEmail, $fromName)
            ->to($this->details['email'])
            ->subject($this->details['subject'])
            ->view($this->details['view'],$this->details['data']);
    }
}
