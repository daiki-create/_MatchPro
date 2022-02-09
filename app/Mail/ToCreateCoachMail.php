<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ToCreateCoachMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($temp_coach)
    {
        $this->temp_coach = $temp_coach;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $assign_data = [
            'temp_passwd' => $this->temp_coach['temp_passwd'],
            'url' => (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . 
                $_SERVER['HTTP_HOST'] . '/temp_coaches/redirect_to_register/' . 
                $this->temp_coach['loginid'] . '/' . $this->temp_coach['temp_code'] . '/' . $this->temp_coach['coach_flag']
        ];
        return $this
            ->subject('本登録のご案内')
            ->view('mail.to_create_coach')
            ->with($assign_data);
    }
}
