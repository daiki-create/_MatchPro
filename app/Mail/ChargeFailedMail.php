<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChargeFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reservation, $coach, $student)
    {
        $this->student = $student;
        $this->coach = $coach;
        $this->reservation = $reservation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $assign_data = [
            'name' => $this->student['name'],
            'coach' => $this->coach,
            'reservation' => $this->reservation
        ];
        return $this
            ->subject('お支払いに失敗しました')
            ->view('mail.charge_failed')
            ->with($assign_data);
    }
}
