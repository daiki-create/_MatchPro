<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MessageRepository implements MessageRepositoryInterface
{
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function save($post_data)
    {
        return $this->message
            ->create([
                'sender_id' => $post_data->sender_id,
                'receiver_id' => $post_data->receiver_id,
                'sender' => $post_data->sender,
                'message' => $post_data->message,
        ]);
    }

    public function saveReservationMessage($reservation_data)
    {
        return $this->message
            ->create([
                'sender_id' => $reservation_data['sender_id'],
                'receiver_id' => $reservation_data['receiver_id'],
                'coach_id' => $reservation_data['coach_id'],
                'student_id' => $reservation_data['student_id'],
                'sender' => $reservation_data['sender'],
                'message' => $reservation_data['message'],
        ]);
    }

    public function getCurrentCoachMessagesBySessionId($session_id)
    {
        $messages = $this->message
        ->where('coach_id', $session_id)
        ->whereIn('id', function($query) {
            $query
            ->select(DB::raw('MAX(id) As id'))
            ->from('messages')
            ->groupBy('student_id');
         })
         ->get();

        if(count($messages) > 0)
        {
            return $messages;
        }
        return FALSE;
    }

    public function getCurrentStudentMessagesBySessionId($session_id)
    {
        $messages = $this->message
        ->where('student_id', $session_id)
        ->whereIn('id', function($query) {
            $query
            ->select(DB::raw('MAX(id) As id'))
            ->from('messages')
            ->groupBy('coach_id');
         })
         ->get();

        if(count($messages) > 0)
        {
            return $messages;
        }
        return FALSE;
    }

    public function getMessagesByCoachId($session_id, $opponent_id)
    {
        $messages = $this->message
        ->where('student_id', $opponent_id)
        ->where('coach_id', $session_id)
        ->get();

        if(count($messages) > 0)
        {
            return $messages;
        }
        return FALSE;
    }

    public function getMessagesByStudentId($session_id, $opponent_id)
    {
        $messages = $this->message
        ->where('student_id', $session_id)
        ->where('coach_id', $opponent_id)
        ->get();

        if(count($messages) > 0)
        {
            return $messages;
        }
        return FALSE;
    }

    public function saveCoachMessage($session_id, $opponent_id, $post_message)
    {
        return $this->message
        ->create([
            'sender_id' => $session_id,
            'receiver_id' => $opponent_id,
            'student_id' => $opponent_id,
            'coach_id' => $session_id,
            'sender' => 'coach',
            'message' => $post_message,
        ]);
    }

    public function saveStudentMessage($session_id, $opponent_id, $post_message)
    {
        return $this->message
        ->create([
            'sender_id' => $session_id,
            'receiver_id' => $opponent_id,
            'student_id' => $session_id,
            'coach_id' => $opponent_id,
            'sender' => 'student',
            'message' => $post_message,
        ]);
    }
}