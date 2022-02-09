<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationCanceledCoachMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reservation, $coach, $student)
    {
        $this->reservation = $reservation;
        $this->coach = $coach;
        $this->student = $student;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $assign_data = [
            'reservation' => $this->reservation,
            'student' => $this->student,
            'name' => $this->coach['name']
        ];
        return $this
            ->subject('予約がキャンセルされました')
            ->view('mail.reservation_canceled_coach')
            ->with($assign_data);
    }
}
