<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUser;
use App\Http\Resources\JWT as JWTResource;
use App\Http\Resources\UserThumbnail as UserThumbnailResource;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function get()
    {
        return (new UserThumbnailResource(auth()->user()))
            ->response()
            ->setStatusCode(200);
    }

    public function store(RegisterUser $request)
    {
        $user = User::forceCreate([
            'email'    => $request->email,
            'name'     => $request->name,
            'password' => Hash::make($request->password),
        ]);

        return (new JWTResource(auth()->login($user)))
            ->response()
            ->setStatusCode(201);
    }
}
