<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name'        => ['required'],
            'price'       => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'max:255'],
            'image'       => ['required', 'image', 'mimes:jpeg,png'],
            'condition'   => ['required'],
            'category'    => ['required', 'array'], // カテゴリーは配列として扱う
            'category.*'  => ['required', 'exists:categories,id'], // 各カテゴリーIDが正しいか確認
        ];
    }

    public function messages()
    {
        return [
            'name.required'        => '商品名は必須です。',
            'price.required'       => '商品価格は必須です。',
            'price.numeric'        => '商品価格は数値で入力してください。',
            'price.min'            => '商品価格は0円以上で設定してください。',
            'description.required' => '商品説明は必須です。',
            'description.max'      => '商品説明は255文字以内で入力してください。',
            'image.required'       => '商品画像は必須です。',
            'image.image'          => '有効な画像ファイルをアップロードしてください。',
            'image.mimes'          => '商品画像は.jpegまたは.png形式でアップロードしてください。',
            'condition.required'   => '商品の状態は必須です。',
            'category.required'    => '商品のカテゴリーは必須です。',
        ];
    }
}
