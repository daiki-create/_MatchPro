<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollAccountMajorRequest extends FormRequest
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
            'bank_select' => 'required',
            'branch' => ['required','string','max:20'],
            'branch_code' => ['required','digits:3'],
            'account_type' => 'required',
            'symbol_number' => ['required','digits:7'],
            'name' => 'required',
        ];
    }

    public function messages(){
        return [
            'branch.required' => '店舗名を入力してください。', 
            'branch.max'  => '店舗名は20文字以内でお願いします。',

            'branch_code.required' => '店舗コードを入力してください。', 
            'branch_code.digits'  => '店舗コードは半角数字3文字でお願いします。',

            'account_type.required'       => '預金種目を入力してください。',

            'symbol_number.required'       => '口座番号を入力してください。',
            'symbol_number.digits'       => '口座番号は半角数字7文字でお願いします。',

            'name.required'       => '預金者名を入力してください。',
            'name.max'       => '預金者名は50文字以内でお願いします。',
        ];
    }
}
