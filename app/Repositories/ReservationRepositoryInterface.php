<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface ReservationRepositoryInterface
{
    public function save($post_data);

    public function getReservationById($id);
    public function getReservationByCoachId($id);
    public function getReservationByStudentId($id);

    public function updateReservationAccepted($id);
    public function updateReservationRejected($id);
    public function updateReservationCanceled($id, $fee);

    public function updateReservationAnswered($id);
    public function updateReservationPassed($id);

    public function getChargeTargetReservations($date);
    public function getImmediateChargeTargetReservations($date, $student_id);
    public function updateChargedFlag($reservation_id);

    function saveReview($post_data);
}