<?php

namespace App\Application\Controllers;

use App\Application\Models\User;
use Illuminate\Support\Facades\Validator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProfileController extends Controller
{
    public function updateProfile(Request $request, Response $response, $args)
    {
        $data = literal(...$request->getParsedBody());

        $validator = Validator::make((array)$data, [
            'first_name'    => 'required|string|min:3|max:50',
            'last_name'     => 'required|string|min:3|max:50',
            'phone_number'  => 'nullable|numeric',
            'date_of_birth' => 'nullable|date|min:3|max:50',
            'gender'        => 'nullable|in:male,female,other',
        ]);

        if ($validator->fails()) {
            return $this->validationFail($validator);
        }

        $user                = User::find($request->getAttribute('user')->id);
        $user->first_name    = $data->first_name;
        $user->last_name     = $data->last_name;
        $user->phone_number  = $data->phone_number ?? null;
        $user->date_of_birth = $data->date_of_birth ?? null;
        $user->gender        = $data->gender ?? null;
        $user->save();

        return $this->respondWithData([
            'status'  => 'success',
            'message' => 'Profile udpated successfully'
        ]);
    }
}
