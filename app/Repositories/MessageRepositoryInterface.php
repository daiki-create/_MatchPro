<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface MessageRepositoryInterface
{
    public function saveReservationMessage($reservation_data);

    public function getCurrentCoachMessagesBySessionId($session_id);
    public function getCurrentStudentMessagesBySessionId($session_id);

    public function getMessagesByCoachId($session_id, $opponent_id);
    public function getMessagesByStudentId($session_id, $opponent_id);

    public function saveCoachMessage($session_id, $opponent_id, $post_message);
    public function saveStudentMessage($session_id, $opponent_id, $post_message);
}