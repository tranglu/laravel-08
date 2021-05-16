<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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

    public function getCurrentUser()
    {
        $user=auth()->user();
        \Log::info("item= " . print_r($user, true));
        return $user;
    }
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Tokens Revoked']);
    }
}
