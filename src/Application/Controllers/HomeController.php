<?php

namespace App\Application\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return $this->respondWithData(['home' => 'home']);
    }
}
