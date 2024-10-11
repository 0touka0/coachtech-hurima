<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'id'    => ['required', 'exists:users,id'],
            'image' => ['image', 'mimes:jpeg,png'],
        ];
    }

    public function messages()
    {
        return [
            'image.image' => '有効な画像ファイルをアップロードしてください。',
            'image.mimes' => 'プロフィール画像は.jpegまたは.png形式でアップロードしてください。',
        ];
    }
}
