<?php

namespace App\Http\Controllers;

use App\Services\MessageService;
use App\Services\MailService;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;

class MessageController extends Controller
{
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;

        $this->login = session()->get('login');
        $this->coach_flag = session()->get('coach_flag');
    }

    public function list()
    {
        $login = session()->get('login');
        $coach_flag = session()->get('coach_flag');
        if($message_lists = $this->messageService->getCurrentMessagesBySessionId($login['id']))
        {
            $assign_data = [
                'message_lists' => $message_lists,
                'coach_flag' => $coach_flag,
            ];
            return view('messages.list', $assign_data);
        }
        return view('messages.list');
    }

    public function talk($opponent_id, $opponent_name)
    {
        if($messages = $this->messageService->getMessagesBySessionId($opponent_id))
        {
            $assign_data = [
                'messages' => $messages,
                'opponent_id' => $opponent_id,
                'opponent_name' => $opponent_name,
                'my_id' => $this->login['id'],
                'my_name' => $this->login['name'],
                'coach_flag' => $this->coach_flag,
            ];
            return view('messages.talk', $assign_data);
        }
        return view('messages.talk');
    }

    public function save(MessageRequest $post_data)
    {
        $login = session()->get('login');
        if($this->messageService->save($post_data->opponent_id, $post_data->message))
        {

        }
        $assign_data = [
            'opponent_id' => $post_data->opponent_id,
            'opponent_name' => $post_data->opponent_name,
            'my_name' => $login['name'],
            'my_id' => $login['id']
        ];
        return redirect(route('messages.talk', $assign_data));
    }
}
