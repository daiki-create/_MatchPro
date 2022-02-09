<?php

namespace App\Repositories;

use App\Models\Coach;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class CoachRepository implements CoachRepositoryInterface
{
    public function __construct(Coach $coach)
    {
        $this->coach = $coach;
        $this->student = app()->make('App\Models\Student');  
    }

    public function getCoachByLoginid($loginid)
    {
        $_coach = $this->coach
            ->where('loginid', $loginid)
            ->first();

        if(!empty($_coach))
        {
            return $_coach;
        }
        return FALSE;
    }

    public function getCoachById($id)
    {
        $_coach = $this->coach
            ->where('id', $id)
            ->first();

        if(!empty($_coach))
        {
            return $_coach;
        }
        return FALSE;
    }

    public function getStudentByLoginid($loginid)
    {
        $_student = $this->student
            ->where('loginid', $loginid)
            ->first();

        if(!empty($_student))
        {
            return $_student;
        }
        return FALSE;
    }

    public function getStudentById($id)
    {
        $_student = $this->student
            ->where('id', $id)
            ->first();

        if(!empty($_student))
        {
            return $_student;
        }
        return FALSE;
    }

    public function save($post_data)
    {
        return $this->coach
            ->create([
                'loginid' => $post_data->loginid,
                'passwd' => password_hash($post_data->passwd, PASSWORD_DEFAULT),
                'name' => $post_data->name,
                'birth' => $post_data->birth,
                'icon' => $post_data->icon,
                'status' => $post_data->status,
                'identified_status' => $post_data->identified_status,
                'penalty' => $post_data->penalty,
        ]);
    }

    public function saveStudent($post_data)
    {
        return $this->student
            ->create([
                'loginid' => $post_data->loginid,
                'passwd' => password_hash($post_data->passwd, PASSWORD_DEFAULT),
                'name' => $post_data->name,
                'birth' => $post_data->birth,
                'icon' => $post_data->icon,
                'status' => $post_data->status,
                'identified_status' => $post_data->identified_status,
        ]);
    }

    public function getCoachList()
    {
        return $this->coach
            ->where('status', 'active')
            ->where('identified_status', 'identified')
            ->get();
    }

    public function updateCoachPenalty($id, $penalty)
    {
        return $this->coach
        ->where('id', $id)
        ->update([
            'penalty' => $penalty
        ]);
    }

    public function updateCoach($post_data)
    {
        return $this->coach
        ->where('loginid', $post_data['loginid'])
        ->update([
            'name' => $post_data['name'],
            'birth' => $post_data['birth'],
            'passwd' => password_hash($post_data['passwd'], PASSWORD_DEFAULT),
        ]);
    }

    public function updateStudent($post_data)
    {
        return $student = $this->student
        ->where('loginid', $post_data['loginid'])
        ->update([
            'name' => $post_data['name'],
            'birth' => $post_data['birth'],
            'passwd' => password_hash($post_data['passwd'], PASSWORD_DEFAULT),
        ]);
    }

    public function updateCoachIcon($post_data)
    {
        return $this->coach
        ->where('loginid', $post_data['loginid'])
        ->update([
            'icon' => $post_data['icon_name'],
        ]);
    }

    public function updateStudentIcon($post_data)
    {
        return $student = $this->student
        ->where('loginid', $post_data['loginid'])
        ->update([
            'icon' => $post_data['icon_name'],
        ]);
    }

    public function updateTraining($post_data)
    {
        return $this->coach
        ->where('loginid', $post_data['loginid'])
        ->update([
            'area' => $post_data['area'],
            'fee' => $post_data['fee'],
            'avalable_datetime' => $post_data['avalable_datetime'],
            'profile' => $post_data['profile'],
        ]);
    }

    public function updatePayjp($id, $payjp_customer_id, $payjp_card_id)
    {
        return $this->student
        ->where('id', $id)
        ->update([
            'payjp_customer_id' => $payjp_customer_id,
            'payjp_card_id' => $payjp_card_id,
        ]);
    }

    public function updateStudentInactive($reservation)
    {
        return $student = $this->student
        ->where('id', $reservation['student_id'])
        ->update([
            'status' => 'inactive',
        ]);
    }

    public function updateStudentActive($reservation)
    {
        return $student = $this->student
        ->where('id', $reservation['student_id'])
        ->where('status', 'inactive')
        ->update([
            'status' => 'active',
        ]);
    }

    public function updateCoachLeft($login)
    {
        return $coach = $this->coach
        ->where('id', $login['id'])
        ->where('status', 'active')
        ->update([
            'status' => 'left',
        ]);
    }

    public function updateStudentLeft($login)
    {
        return $student = $this->student
        ->where('id', $login['id'])
        ->where('status', 'active')
        ->update([
            'status' => 'left',
        ]);
    }

    public function updateCoachParmanent($reservation)
    {
        return $coach = $this->coach
        ->where('id', $reservation['coach_id'])
        ->where('status', 'active')
        ->update([
            'status' => 'parmnent',
        ]);
    }

    public function updateCoachChecking($coach_id)
    {
        return $this->coach
        ->where('id', $coach_id)
        ->update([
            'identified_status' => 'checking',
        ]);
    }

    public function updateStudentChecking($student_id)
    {
        return $this->student
        ->where('id', $student_id)
        ->update([
            'identified_status' => 'checking',
        ]);
    }

    public function getAllCoachs()
    {
        return $this->coach
        ->get();
    }

    public function getAllStudents()
    {
        return $this->student
        ->get();
    }
}