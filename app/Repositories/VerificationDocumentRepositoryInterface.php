<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface VerificationDocumentRepositoryInterface
{
    public function saveCoachDocument($post_data);
    public function saveStudentDocument($post_data);

    public function getCoachDocumentById($session_id);
    public function getStudentDocumentById($session_id);

    public function deleteCoachDocument($coach_document);
    public function deleteStudentDocument($student_document);
}