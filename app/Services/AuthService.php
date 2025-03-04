<?php
namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\User;
use App\Traits\ResponseFormatterTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService
{
    use ResponseFormatterTrait;

    public function __construct(
        private User $user
    ) {}

    public function signIn($request) {

        $data = $request->all();
        $credentials = Arr::only($data, ['username', 'password']);
        $user = $this->user->where('username', $credentials['username'])->first();

        if (!$token = $this->authAttempt($credentials)) {
            throw BusinessRuleException::invoke('Invalid credentials');
        }

        $data = [
            'user' => $user,
            'authorization' => $this->respondWithToken($token)
        ];

        return $data;
    }

    public function authAttempt($credentials)
    {
        return auth('api')->attempt($credentials);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return array
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    public function signUp() {}

    public function logout() 
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseError(message:'Unauthorized', error: ['error' => 'Invalid Credential!'], status: Response::HTTP_UNAUTHORIZED);
            }
            auth('api')->logout();
            return $this->responseSuccess(message: 'Successfully logged out', data: ['message' => 'Successfully logged out']);
        } catch (TokenExpiredException $e) {
            return $this->responseError(message:'Unauthorized', error: ['error' => 'token expired'], status: Response::HTTP_UNAUTHORIZED);
        } catch (TokenInvalidException $e) {
            return $this->responseError(message:'Unauthorized', error: ['error' => 'token invalid'], status: Response::HTTP_UNAUTHORIZED);
        } catch (JWTException $e) {
            return $this->responseError(message:'Unauthorized', error: ['error' => 'token absent!'], status: Response::HTTP_UNAUTHORIZED);
        }
    }
}