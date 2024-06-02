<?php
namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class TodoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
        ];
    }

    public function getTodoData()
    {

        $validated = $this->validated();
        if (Auth::check()) {
            $userId = Auth::user()->id;
        }
        return [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'completed' => $validated['sttaus'] ?? false,
            'user_id'=>$userId 
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
