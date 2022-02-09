<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'birth' => ['required','date'],
            'name' => ['required','string','max:50'],
            'passwd' => ['required','string','min:6','max:50'],
        ];
    }

    public function messages(){
        return [
            'loginid.required'  => '不正なアクセスです。',
            'loginid.email'  => '不正なアクセスです。',
            'loginid.max'  => '不正なアクセスです。',

            'birth.required'  => '生年月日を選択してください。',
            'birth.date'  => '生年月日は日付の形式でお願いします。',

            'name.required'  => '氏名を入力してください。',
            'name.max'  => '氏名は50文字以内でお願いします。',

            'passwd.required'  => 'パスワードを入力してください。',
            'passwd.min'  => 'パスワードは6文字以上でお願いします。',
            'passwd.max'  => 'パスワードは50文字以内でお願いします。',

            'coach_flag.required'       => '不正なアクセスです。',
        ];
    }
}