<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth; // JWTAuth facade to work with JWT tokens
use Tymon\JWTAuth\Exceptions\TokenExpiredException; // Exception when token is expired
use Tymon\JWTAuth\Exceptions\TokenInvalidException; // Exception when token is invalid

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request  // The HTTP request instance
     * @param  \Closure  $next  // The next middleware or request handler
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = JWTAuth::getToken($request);
        if (!$token) {
            return response()->json(['error' => 'Authorization Token not found'], 401);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token is Invalid'], 401);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token is Expired'], 401);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while verifying token'], 401);
        }

        return $next($request);
    }
}
