<?php

namespace App\Http\Controllers;

use App\Http\Resources\Auth\AuthenticatedResource;
use App\Services\AuthService;
use App\Traits\ResponseFormatterTrait;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ResponseFormatterTrait;

    public function __construct(private AuthService $authService)
    {}

    public function signIn(Request $request)
    {
        $data = $this->authService->signIn($request);

        $data['user'] = AuthenticatedResource::make($data['user']);

        return $this->responseSuccess(data: $data);
    }

    public function logout()
    {
       return $this->authService->logout();
    }
}
