<?php

namespace App\Services;

use App\Repositories\MessageRepositoryInterface as MessageRepository;
use Illuminate\Database\Eloquent\Model;

class MessageService
{
    public function __construct(
        MessageRepository $messageRepository
    )
    {
        $this->messageRepository = $messageRepository;
        $this->CoachRepository = app()->make('App\Repositories\CoachRepositoryInterface');
    }

    public function getCurrentMessagesBySessionId($session_id)
    {
        $coach_flag = session()->get('coach_flag');
        if($coach_flag)
        {
            if($message_lists = $this->messageRepository->getCurrentCoachMessagesBySessionId($session_id))
            {
                // 生徒の名前を取得
                foreach($message_lists as $message_list)
                {
                    if($student = $this->CoachRepository->getStudentById($message_list['student_id']))
                    {
                        $message_list['opponent_name'] = $student['name'];
                    }
                }
                return $message_lists;
            }
        }
        else
        {
            if($message_lists = $this->messageRepository->getCurrentStudentMessagesBySessionId($session_id))
            {
                // コーチの名前を取得
                foreach($message_lists as $message_list)
                {
                    if($coach = $this->CoachRepository->getCoachById($message_list['coach_id']))
                    {
                        $message_list['opponent_name'] = $coach['name'];
                    }
                }
                return $message_lists;
            }
        }
        return FALSE;
    }

    public function getMessagesBySessionId($opponent_id)
    {
        $login = session()->get('login');
        $coach_flag = session()->get('coach_flag');
        if($coach_flag)
        {
            if($messages = $this->messageRepository->getMessagesByCoachId($login['id'], $opponent_id))
            {
                return $messages;
            }
        }
        else
        {
            if($messages = $this->messageRepository->getMessagesByStudentId($login['id'], $opponent_id))
            {
                return $messages;
            }
        }
        return FALSE;
    }

    public function save($opponent_id, $post_message)
    {
        $login = session()->get('login');
        $coach_flag = session()->get('coach_flag');
        if($coach_flag)
        {
            return $this->messageRepository->saveCoachMessage($login['id'], $opponent_id, $post_message);
        }
        else
        {
            return $this->messageRepository->saveStudentMessage($login['id'], $opponent_id, $post_message);
        }
        return FALSE;
    }
}