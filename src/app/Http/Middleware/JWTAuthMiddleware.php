<?php

namespace App\Http\Middleware;

use App\Enums\StatusEnums;
use App\Traits\ResponseFormatterTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JWTAuthMiddleware
{
    use ResponseFormatterTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::user();

            JWTAuth::parseToken()->authenticate();

            return $next($request);
        } catch (TokenExpiredException $e) {
            return $this->responseError(message: 'User token expired. Please re-login.', status: Response::HTTP_UNAUTHORIZED);
        } catch (TokenInvalidException $e) {
            return $this->responseError(message: 'Unauthenticated', status: Response::HTTP_UNAUTHORIZED);
        } catch (JWTException $e) {
            return $this->responseError(message: 'Unauthenticated', status: Response::HTTP_UNAUTHORIZED);
        }
    }
}
