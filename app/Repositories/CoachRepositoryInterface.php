<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface CoachRepositoryInterface
{
    public function getCoachByLoginid($loginid);
    public function getCoachById($id);
    public function getStudentByLoginid($loginid);
    public function getStudentById($id);

    public function save($post_data);

    public function saveStudent($post_data);

    public function getCoachList();

    public function updateCoachPenalty($id, $penalty);

    public function updateCoach($post_data);
    public function updateStudent($post_data);

    public function updateCoachIcon($post_data);
    public function updateStudentIcon($post_data);

    public function updateTraining($post_data);

    public function updatePayjp($id, $payjp_customer_id, $payjp_card_id);

    public function updateStudentInactive($reservation);
    public function updateStudentActive($reservation);

    public function updateCoachLeft($login);
    public function updateStudentLeft($login);
    
    public function updateCoachParmanent($reservation);

    public function updateCoachChecking($coach_id);
    public function updateStudentChecking($student_id);

    public function getAllCoachs();
    public function getAllStudents();
}