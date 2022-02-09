<?php

namespace App\Repositories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;   
    }

    public function save($post_data)
    {
        return $this->reservation
            ->create([
            'coach_id' => $post_data['id'],
            'student_id' => $post_data['student_id'],
            'date' => $post_data['date'],
            'start_time' => $post_data['start_time'],
            'end_time' => $post_data['end_time'],
            'fee' => $post_data['fee'] * $post_data['num'],
            'num' => $post_data['num'],
            'content' => $post_data['content'],
            'charged_flag' => 0,
    ]);
    }

    public function getReservationById($id)
    {
        $reservation = $this->reservation
        ->where('id', $id)
        ->first();

        if($reservation)
        {
            return $reservation;
        }
        return FALSE;
    }

    public function getReservationByCoachId($id)
    {
        $reservations = $this->reservation
        ->where('coach_id', $id)
        ->get();

        if(count($reservations) > 0)
        {
            return $reservations;
        }
        return FALSE;
    }

    public function getReservationByStudentId($id)
    {
        $reservations = $this->reservation
        ->where('student_id', $id)
        ->get();

        if(count($reservations) > 0)
        {
            return $reservations;
        }
        return FALSE;
    }

    public function updateReservationAccepted($id)
    {
        return $reservations = $this->reservation
        ->where('id', $id)
        ->update([
            'status' => 'accepted'
        ]);
    }

    public function updateReservationRejected($id)
    {
        return $reservations = $this->reservation
        ->where('id', $id)
        ->update([
            'status' => 'rejected'
        ]);
    }

    public function updateReservationCanceled($id, $fee)
    {
        return $reservations = $this->reservation
        ->where('id', $id)
        ->update([
            'status' => 'canceled',
            'fee' => $fee
        ]);
    }

    public function updateReservationAnswered($id)
    {
        return $reservations = $this->reservation
        ->where('id', $id)
        ->update([
            'answered_flag' => 'answered'
        ]);
    }

    public function updateReservationPassed($id)
    {
        return $reservations = $this->reservation
        ->where('id', $id)
        ->update([
            'answered_flag' => 'passed'
        ]);
    }

    public function getChargeTargetReservations($date)
    {
        $reservations = $this->reservation
        ->where('date', '<=', $date)
        ->where('charged_flag', 0)
        ->where(function($query){
            $query
                ->where('status', 'accepted')
                ->orWhere('status', 'canceled');
        })
        ->get();

        if(count($reservations) > 0)
        {
            return $reservations;
        }
        return FALSE;
    }

    public function getImmediateChargeTargetReservations($date, $student_id)
    {
        $reservations = $this->reservation
        ->where('date', '<=', $date)
        ->where('charged_flag', 0)
        ->where('student_id', $student_id)
        ->where(function($query){
            $query
                ->where('status', 'accepted')
                ->orWhere('status', 'canceled');
        })
        ->get();

        if(count($reservations) > 0)
        {
            return $reservations;
        }
        return FALSE;
    }

    public function updateChargedFlag($reservation_id)
    {
        return $reservations = $this->reservation
        ->where('id', $reservation_id)
        ->update([
            'charged_flag' => 1
        ]);
    }

    function saveReview($post_data)
    {
        return $this->reservation
            ->where('id', $post_data['reservation_id'])
            ->update([
            'review' => $post_data['review'],
            'answered_flag' => 'answered',
    ]);
    }
}