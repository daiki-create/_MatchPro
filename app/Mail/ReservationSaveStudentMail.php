<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationSaveStudentMail extends Mailable
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
            'coach' => $this->coach,
            'name' => $this->student['name'],
        ];
        return $this
            ->subject('仮予約完了のお知らせ')
            ->view('mail.reservation_save_student')
            ->with($assign_data);
    }
}
