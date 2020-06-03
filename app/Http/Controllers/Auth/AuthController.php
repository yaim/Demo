<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use App\Http\Resources\JWT as JWTResource;

class AuthController extends Controller
{
    public function store(Login $request)
    {
        $token = auth()->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        if (!$token) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        return (new JWTResource($token))
            ->response()
            ->setStatusCode(201);
    }
}
