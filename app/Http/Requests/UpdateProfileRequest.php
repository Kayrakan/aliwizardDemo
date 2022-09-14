<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'name' => 'required|string',
            'old_password'=>'nullable',
            'password' => 'confirmed|nullable|different:old_password|required_with:old_password',
            "password_confirmation" =>"nullable|required_with:new_password|required_with:old_password|same:password"
        ];
    }
}
