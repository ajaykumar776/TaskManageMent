<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required','string','email',Rule::exists('users')],
            'password' => 'required|string',
        ];
    }

    public function getLoginData()
    {

        $validated = $this->validated();
        return [
            'email' => $validated['email'],
            'password' => $validated['name']
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => 422,
            'message' => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
