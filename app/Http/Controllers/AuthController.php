<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        if(!$request->ajax()){
            return $this->errorNotAjax();
        }

        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email']
        ]);// tạo user mới

        return response()->json([
            'status_code' => 200,
        ]);
//        return $this->success([
////            'token' => $user->createToken('API Token')->plainTextToken
//        ]);// tạo token mới
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Unauthorized'
                ]);
            }

            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in Login');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;//

            return response()->json([
                'status_code' => 200,
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }
    }

    public function deny(): JsonResponse
    {
        return response()->json(['message'=>'AJAX Request Only, Access Denied'],4010);
    }

    public function check(Request $request): JsonResponse
    {
        if(!$request->ajax()){
            return $this->errorNotAjax();
        }
        $checkArray['status'] = 'nok';
        $checkArray['message'] = 'unauthorized';
        $check = auth()->check();
        if ($check) {
            $checkArray['status'] = 'ok';
            $checkArray['message'] = 'authenticated';
        }
        return response()->json($checkArray);
    }
}