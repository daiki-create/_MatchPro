<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TempCoachRequest extends FormRequest
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
            'loginid' => ['required','email','max:50'],
            'coach_flag' => ['required'],
        ];
    }

    public function messages(){
        return [
            'loginid.required'  => 'メールアドレスを入力してください。',
            'loginid.email'  => 'メールアドレスの形式が間違っています。',
            'loginid.max'  => 'メールアドレスは50文字以内でお願いします。',
            'coach_flag.required'       => '不正なアクセスです。',
        ];
    }
}