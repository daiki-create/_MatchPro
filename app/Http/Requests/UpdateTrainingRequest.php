<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingRequest extends FormRequest
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
            'area' => ['required','string','max:100'],
            'fee' => ['required','numeric','min:50','max:10000'],
            'avalable_datetime' => ['required','string','max:400'],
            'profile' => ['required','string','max:400'],
        ];
    }

    public function messages(){
        return [
            'area.required'  => '練習場所を入力してください。',
            'area.max'  => '練習場所は100文字以内でお願いします。',
            
            'fee.required'  => '30分あたりの料金を入力してください。',
            'fee.numeric'  => '30分あたりの料金は半角数字でお願いします。',
            'fee.min'  => '30分あたりの料金は50円以上でお願いします。',
            'fee.max'  => '30分あたりの料金は10000以内でお願いします。',

            'avalable_datetime.required'  => '予約の取りやすい日時を入力してください。',
            'avalable_datetime.max'  => '予約の取りやすい日時は400文字以内でお願いします。',

            'profile.required'  => '自己紹介・練習内容を入力してください。',
            'profile.max'  => '自己紹介・練習内容は400文字以内でお願いします。',

            'coach_flag.required'       => '不正なアクセスです。',
        ];
    }
}