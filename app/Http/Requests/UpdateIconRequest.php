<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIconRequest extends FormRequest
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
            'icon' => ['required','max:6400','file','mimes:png,jpg,jpeg'],
            'coach_flag' => ['required'],
        ];
    }

    public function messages(){
        return [
            'icon.required'  => '画像を選択してください。',
            'icon.size'  => '画像は6.4MB以下でお願いします。',
            'icon.file'  => 'ファイルを選択してください。',
            'icon.mimes'  => '画像はpng,jpg,jpegのいずれかの形式でおねがいします。',

            'coach_flag.required'       => '不正なアクセスです。',
        ];
    }
}