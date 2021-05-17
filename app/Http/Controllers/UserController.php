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
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => User::all(),
        ]);
    }

    public function getCurrentUser(): JsonResponse
    {
        $user=auth()->user();
//        $x=new UserResource(User::find($user->id));
//        \Log::info("user data= " . print_r($x, true));
//        return response()->json([
//            'data' => $user,
//        ]);

        return response()->json(['data' => new UserResource($user), 'message' => 'Retrieved successfully'], 200);
    }
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Tokens Revoked']);
    }
}
