<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'type' => 'required',
        'name' => 'required | min:5',
            'price' => 'required | integer | min:50000',
            'description' => 'required | min:20',
            'image' => 'file | mimes:jpeg,jpg,png,svg'
        ];
    }
}
