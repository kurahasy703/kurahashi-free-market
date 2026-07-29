<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * 認可
     */
    public function authorize()
    {
        return true;
    }

    /**
     * バリデーション
     */
    public function rules()
    {
        return [
            'payment_method' => [
                'required',
                'in:コンビニ払い,カード支払い',
            ],
        ];
    }

    /**
     * エラーメッセージ
     */
    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください。',
            'payment_method.in' => '正しい支払い方法を選択してください。',
        ];
    }
}
