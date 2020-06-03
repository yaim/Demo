<?php

namespace App\Http\Controllers\Auth;

use App\Events\LogInDenied;
use App\Events\LoggedInSuccessfully;
use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use App\Http\Resources\JWT as JWTResource;
use App\User;

class AuthController extends Controller
{
    public function store(Login $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $token = auth()->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        if (!$token) {
            event(new LogInDenied($user, now()));

            return $this->unauthorizedResponse();
        }

        event(new LoggedInSuccessfully($user, now()));

        return (new JWTResource($token))
            ->response()
            ->setStatusCode(201);
    }

    private function unauthorizedResponse()
    {
        return response()->json([
            'error' => 'Unauthorized'
        ], 401);        
    }
}
