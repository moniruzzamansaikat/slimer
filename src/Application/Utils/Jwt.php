<?php

namespace App\Application\Utils;

use App\Application\Models\User;
use Firebase\JWT\JWT as JWTJWT;

class Jwt
{
    private static function getUserInfo($userId)
    {
        $user = User::find($userId);

        if (!$user) return $user;

        return [
            'id'         => $user->id,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email,
            'role'       => $user->role
        ];
    }

    public static function tokenByUserId($userId)
    {
        $payload = [
            'iss'  => env('JWT_ISS', 'localhost'),
            'sub'  => $userId,
            'iat'  => time(),
            'exp'  => time() + env('JWT_TIME', 3600),
            'user' => self::getUserInfo($userId)
        ];

        $token = JWTJWT::encode($payload, env('JWT_SECRET', 'random'), 'HS256');

        return $token;
    }
}
