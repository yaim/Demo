<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginAttempt as LoginAttemptResource;

class SuccessfulAttemptController extends Controller
{
    public function index()
    {
        $attempts = auth()->user()
            ->loginAttempts()
            ->where('successful', 1)
            ->orderBy('attempted_at', 'desc')
            ->take(5)
            ->get();

        return LoginAttemptResource::collection($attempts);
    }
}
