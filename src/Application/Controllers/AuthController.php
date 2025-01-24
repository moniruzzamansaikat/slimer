<?php

namespace App\Application\Controllers;

use App\Application\Models\User;
use App\Application\Utils\Jwt;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends Controller
{
    public function register(Request $request, $response, $args)
    {
        $data = $request->getParsedBody() ?? [];

        $validator = Validator::make($data, [
            'first_name' => 'required|string|min:3|max:50',
            'last_name'  => 'required|string|min:3|max:50',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|confirmed|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->respondWithData([
                'error'  => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        return $this->respondWithData(['lol']);
    }

    public function login(Request $request, $response, $args)
    {
        $data = $request->getParsedBody() ?? [];

        $validator = Validator::make($data, [
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->respondWithData([
                'error'  => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $user = User::where('email', $data['email'])->first();

        $valid = Hash::check($data['password'], $user->password);

        if (!$valid) {
            return $this->respondWithData([
                'error' => 'Invalid password',
            ], 400);
        }

        // $user->last_login_at = (new Carbon())->now();
        $user->last_login_at = Carbon::now();
        $user->save();

        return $this->respondWithData([
            'token' => Jwt::tokenByUserId($user->id),
            'user'  => $user
        ]);
    }
}
