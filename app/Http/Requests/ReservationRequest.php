<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
            'date' => ['required','date_format:Y-m-d'],
            'start_time' => ['required','date_format:H:i'],
            'end_time' => ['required','date_format:H:i','after:start_time'],
            'num' => ['required','integer','between:1,50'],
            'content' => ['string','max:200'],
        ];
    }

    public function messages(){
        return [
            'date.required'       => '希望する日付を入力してください。',
            'date.date_format'       => '希望する日付の形式が間違っています。',

            'start_time.required'       => '希望する開始時刻を入力してください。',
            'start_time.date_format'       => '希望する開始時刻の形式が間違っています。',

            'end_time.required'       => '希望する終了時刻を入力してください。',
            'end_time.date_format'       => '希望する終了時刻の形式が間違っています。',
            'end_time.after'       => '希望する終了時刻は開始時刻より後にしてください。',

            'num.required'       => 'ご予約人数を入力してください。',
            'num.integer'       => 'ご予約人数は整数で入力してください。',
            'num.between'       => 'ご予約人数は1～50の範囲で入力してください。',

            'content.max'       => '自由記入欄は200文字以内でお願いします。',
        ];
    }
}
