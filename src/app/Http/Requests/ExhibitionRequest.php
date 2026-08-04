<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * このリクエストを許可するか
     */
    public function authorize()
    {
        return true;
    }

    /**
     * バリデーションルール
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
                'max:255',
            ],

            'image_url' => [
                'required',
                'image',
                'mimes:jpeg,png',
            ],

            'categories' => [
                'required',
                'array',
                'min:1',
            ],

            'categories.*' => [
                'exists:categories,id',
            ],

            'condition_id' => [
                'required',
                'exists:conditions,id',
            ],

            'price' => [
                'required',
                'integer',
                'min:0',
            ],

            'brand_name' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * エラーメッセージ
     */
    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'name.max'
            => '商品名は255文字以内で入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',

            'image_url.required' => '商品画像を選択してください',
            'image_url.image' => '画像ファイルを選択してください',
            'image_url.mimes' => 'JPEGまたはPNG形式の画像を選択してください',

            'categories.required' => 'カテゴリーを選択してください',

            'condition_id.required' => '商品の状態を選択してください',

            'price.required' => '販売価格を入力してください',
            'price.integer' => '販売価格は数値で入力してください',
            'price.min' => '販売価格は0円以上で入力してください',
        ];
    }
}
