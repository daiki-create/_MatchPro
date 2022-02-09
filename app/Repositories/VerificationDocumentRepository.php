<?php

namespace App\Repositories;

use App\Models\VerificationDocument;
use Illuminate\Database\Eloquent\Model;

class VerificationDocumentRepository implements VerificationDocumentRepositoryInterface
{
    public function __construct(VerificationDocument $verificationDocument)
    {
        $this->verificationDocument = $verificationDocument;
    }

    public function saveCoachDocument($post_data)
    {
       return $this->verificationDocument
            ->updateOrCreate([
                'coach_id' => $post_data['session_id'],
                'img_front' => $post_data['img_front_name'],
                'img_back' => $post_data['img_back_name'],
                'document_type' => $post_data['document_type']
            ]);
    }

    public function saveStudentDocument($post_data)
    {
       return $this->verificationDocument
            ->updateOrCreate([
                'student_id' => $post_data['session_id'],
                'img_front' => $post_data['img_front_name'],
                'img_back' => $post_data['img_back_name'],
                'document_type' => $post_data['document_type']
            ]);
    }

    public function getCoachDocumentById($session_id)
    {
        $coach_document = $this->verificationDocument
        ->where('coach_id', $session_id)
        ->first();

        if(!empty($coach_document))
        {
            return $coach_document;
        }
        return FALSE;
    }

    public function getStudentDocumentById($session_id)
    {
        $student_document = $this->verificationDocument
        ->where('student_id', $session_id)
        ->first();

        if(!empty($student_document))
        {
            return $student_document;
        }
        return FALSE;
    }

    public function deleteCoachDocument($coach_document)
    {
        return $this->verificationDocument
        ->where('coach_id', $coach_document['coach_id'])
        ->delete();
    }

    public function deleteStudentDocument($student_document)
    {
        return $this->verificationDocument
        ->where('student_id', $student_document['student_id'])
        ->delete();
    }
}