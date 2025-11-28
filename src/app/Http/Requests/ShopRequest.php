<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->role === 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'region' => 'nullable|max:50',
            'genre' => 'nullable|max:50',
            'description' => 'required|max:255',
            'image' => 'required|image|max:10240',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '店舗名',
            'region' => '地域',
            'genre' => 'ジャンル',
            'description' => '店舗説明',
            'image' => '店舗画像',
        ];
    }
}
