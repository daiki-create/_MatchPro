<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerificationDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'document_type' => ['required'],
            'img_front' => ['required','max:6400','file','mimes:png,jpg,jpeg'],
            'img_back' => ['max:6400','file','mimes:png,jpg,jpeg'],
        ];
    }

    public function messages(){
        return [
            'img_front.required'  => 'オモテ画像を選択してください。',
            'img_front.size'  => 'オモテ画像は6.4MB以下でお願いします。',
            'img_front.file'  => 'ファイルを選択してください。',
            'img_front.mimes'  => 'オモテ画像はpng,jpg,jpegのいずれかの形式でおねがいします。',

            'img_back.size'  => 'ウラ画像は6.4MB以下でお願いします。',
            'img_back.file'  => 'ファイルを選択してください。',
            'img_back.mimes'  => 'ウラ画像はpng,jpg,jpegのいずれかの形式でおねがいします。',
        ];
    }
}
