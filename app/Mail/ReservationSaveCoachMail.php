<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationSaveCoachMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($post_data, $coach, $student)
    {
        $this->post_data = $post_data;
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
            'post_data' => $this->post_data,
            'student' => $this->student,
            'name' => $this->coach['name']
        ];
        return $this
            ->subject('仮予約を受け付けました')
            ->view('mail.reservation_save_coach')
            ->with($assign_data);
    }
}
