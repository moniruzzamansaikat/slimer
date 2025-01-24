<?php

namespace App\Application\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Models\User;
use Illuminate\Database\Capsule\Manager as Capsule; // Import Capsule
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\DatabasePresenceVerifier;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends Controller
{
    public function list(Request $request): Response
    {
        $users = User::paginate();

        return $this->respondWithData([
            'users' => $users,
            'user'  => $request->getAttribute('user')
        ]);
    }

    public function show(Request $request, $response, $args)
    {
        $userId = (int) $args['id'];

        $user = User::find($userId);

        return $this->respondWithData($user);
    }

    public function store(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();

        $validator = Validator::make($data, [
            'first_name' => 'required|string|min:3|max:50',
            'last_name'  => 'required|string|min:3|max:50',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->respondWithData([
                'error'   => 'Validation failed',
                'errors'  => $validator->errors()
            ], 400);
        }

        $firstName = $data['first_name'] ?? null;
        $lastName  = $data['last_name'] ?? null;
        $email     = $data['email'] ?? null;
        $password  = $data['password'] ?? null;

        try {
            $user = new User();
            $user->first_name = $firstName;
            $user->last_name  = $lastName;
            $user->email      = $email;
            $user->password   = $password;
            $user->save();

            return $this->respondWithData(['message' => 'User created successfully', 'data' => $user], 201);
        } catch (\Exception $e) {
            return $this->respondWithData([
                'error'   => 'Validation failed',
                'details' => $e->getMessage(),
            ], 400);
        }
    }
}
