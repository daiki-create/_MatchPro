<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class AdminService
{
    public function __construct()
    {
        $this->coachRepository = app()->make('App\Repositories\CoachRepositoryInterface');
        $this->reservationRepository = app()->make('App\Repositories\ReservationRepositoryInterface');

        $this->login = session()->get('login');
        $this->date = date('Y-m-d');
    }

    function updateAllUserSession()
    {
        if($coaches = $this->coachRepository->getAllCoachs())
        {
            foreach($coaches as $coache)
            {
                
            }
        }
        if($students = $this->coachRepository->getAllStudents())
        {
            foreach($students as $students)
            {

            }
        }
    }
}