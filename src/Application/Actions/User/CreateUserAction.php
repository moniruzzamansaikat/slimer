<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Application\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Psr\Http\Message\ResponseInterface as Response;

class CreateUserAction extends Action
{
    protected function action(): Response
    {
        $request = $this->request;
        $data    = $request->getParsedBody();

        $rules = [
            'first_name' => 'required|string|min:3|max:50',
            'last_name'  => 'required|string|min:3|max:50',
            'email'      => 'required|email',
            'password'   => 'required|string|min:8',
        ];

        $validation = Validator::make($data, $rules, [
            'last_name.required' => 'Last Name is requried'
        ]);

        if ($validation->fails()) {
            return $this->respondWithData([
                'error'   => 'Validation failed',
                'errors' => $validation->errors()
            ], 400);
        }

        $firstName = $data['first_name'] ?? null;
        $lastName  = $data['last_name'] ?? null;
        $email     = $data['email'] ?? null;
        $password  = $data['password'] ?? null;

        try {
            $user             = new User();
            $user->first_name = $firstName;
            $user->last_name  = $lastName;
            $user->email      = $email;
            $user->password   = $password;
            $user->save();

            return $this->respondWithData(['message' => 'User created successfully'], 201);
        } catch (\Exception $e) {
            return $this->respondWithData([
                'error'   => 'Validation failed',
                'details' => $e->getMessage(),
            ], 400);
        }
    }
}
