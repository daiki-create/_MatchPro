<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterCardMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($login)
    {
        $this->login = $login;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $assign_data = [
            'name' => $this->login['name']
        ];
        return $this
            ->subject('新しいお支払い情報を登録しました')
            ->view('mail.register_card')
            ->with($assign_data);
    }
}
