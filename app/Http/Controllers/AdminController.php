<?php

namespace App\Http\Controllers;

use App\Services\CardService;
use App\Services\MailService;
use Facade\FlareClient\View;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;

        $this->login = session()->get('login');
    }
    
    function updateAllUserSessionButton()
    {
        return view('update_all_user_session_button');
    }

    function updateAllUserSession(Request $post_data)
    {

    }
}
