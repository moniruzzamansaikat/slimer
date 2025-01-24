<?php

namespace App\Application\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Models\User;
use Illuminate\Support\Facades\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends Controller
{
    public function index()
    {
        return $this->respondWithData(['home' => 'home']);
    }
}
