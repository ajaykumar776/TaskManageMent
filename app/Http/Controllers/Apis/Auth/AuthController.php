<?php

namespace App\Http\Controllers\Apis\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\UserModel;
use App\Utilities\Output;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function create(RegisterRequest $request)
    {
        try {
            $user = UserModel::create($request->getUserData());
    
            return Output::success(__("response.REGISTRATION_SUCCESS"), $user);
        }catch (\Exception $e) {
            Log::info('User registration failed: ' . $e->getMessage());
            return Output::error($e->getMessage());
        }
    }
    

    public function login(LoginRequest $request)
    {
        try {
            $user = UserModel::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => [__('auth.INVALID_CREDENTIALS')],
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return Output::success(__("response.LOGIN_SUCCESS"), ['token'=>$token,'user'=>$user]);
        } catch (\Exception $e) {
            Log::error('User login failed: ' . $e->getMessage());
            return Output::error(__("response.LOGIN_FAILED"));
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return Output::success(__("response.LOGGED_OUT_SUCCESSFULLY"));
        } catch (\Exception $e) {
            Log::error('User logout failed: ' . $e->getMessage());
            return Output::error(__("response.LOGOUT_FAILED"));
        }
    }
}
