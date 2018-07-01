<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class RefreshTokenAutomatically extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 400);
        }

        try {

            $user = $this->auth->authenticate($token);

        } catch (TokenExpiredException $e) {
            // If the token is expired, then it will be refreshed and added to the headers
            try
            {
                $refreshed = $this->auth->refresh($this->auth->setRequest($request)->getToken());
                $user = $this->auth->setToken($refreshed)->toUser();
                header('Authorization' . $refreshed);
            }
            catch (JWTException $e)
            {
                return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
            }

        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        }

        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }

//        $this->events->fire('tymon.jwt.valid', $user);

        Auth::login($user,false);

        return $next($request);
    }
}
