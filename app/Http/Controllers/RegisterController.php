<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use App\Services\RegisterService;

class RegisterController extends Controller
{
    public function index(SignUpRequest $request, RegisterService $registerService)
    {
        $response = $registerService->handle($request);

        return $response;
    }
}
