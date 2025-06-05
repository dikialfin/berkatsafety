<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class TutorRegisterRequest extends FormRequest
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
            'name' => ['required', 'min:6', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'numeric'],
            'password' => ['required', Password::default()],

            'housetel' => ['required', 'numeric'],
            'address' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'numeric', 'digits:5'],

            'rate_min' => ['required', 'numeric'],
            'rate_max' => ['required', 'numeric'],
            // 'rate_primary_min' => ['required', 'numeric'],
            // 'rate_primary_max' => ['required', 'numeric'],
            // 'rate_secondary_min' => ['required', 'numeric'],
            // 'rate_secondary_max' => ['required', 'numeric'],
            // 'rate_jc_min' => ['required', 'numeric'],
            // 'rate_jc_max' => ['required', 'numeric'],

            'year_of_birth' => ['required', 'numeric'],
            'nationality' => ['required'],
            'race' => ['required'],
            'gender' => ['required'],
            'transport' => ['required'],
        ];
    }
}
