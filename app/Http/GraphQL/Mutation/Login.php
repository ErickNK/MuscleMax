<?php

namespace App\GraphQL\Mutation;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Login
{

    /**
     * Resolve the mutation.
     *
     * @param  mixed  $root
     * @param  array  $args
     * @return mixed
     */
    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        try {
            if (! $token = JWTAuth::attempt(array_only($args,['email','password']))) {
                throw new Exception(json_encode(['authentication_failure']));
            }
        } catch (JWTException $e) {
            return false;
        }
        return [
            'token' => $token,
            'user' => Auth::guard()->user(),
        ];
    }
}
