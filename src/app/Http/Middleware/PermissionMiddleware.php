<?php

namespace App\Http\Middleware;

use App\Traits\ResponseFormatterTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    use ResponseFormatterTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $roles =$user->roles->pluck('name')->toArray();

        if (!in_array('Admin', $roles)) {
            auth('api')->logout();
            return $this->responseError(message: 'User Forbidden Access.', status: HttpResponse::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
