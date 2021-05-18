<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function index(Request $request): JsonResponse
    {
        if(!$request->ajax()){
            return $this->errorNotAjax();
        }
        return response()->json([
            'status_code' => 200,
            'data' => User::all(),
        ]);
    }

    public function getCurrentUser(Request $request): JsonResponse
    {
        if(!$request->ajax()){
            return $this->errorNotAjax();
        }
        $user=auth()->user();
        return response()->json([
            'data' => new UserResource($user),
            'message' => 'Retrieved successfully',
            'status_code' => 200,
        ], 200);
    }
    public function logout(Request $request): JsonResponse
    {
        if(!$request->ajax()){
            return $this->errorNotAjax();
        }
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Tokens Revoked',
            'status_code' => 200,
            ]);
    }
}
