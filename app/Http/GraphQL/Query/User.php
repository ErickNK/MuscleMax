<?php

namespace App\Http\GraphQL\Query;

use App\Service\UserService;
use App\Util\CRUD\HandlesGraphQLQueryRequest;
use GraphQL\Type\Definition\ResolveInfo;


class User
{
    use HandlesGraphQLQueryRequest;


    public function __construct(UserService $CRUDService)
    {
        $this->CRUDService = $CRUDService;
        $this->modelType = "App\\Models\\User";
    }


    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        if(isset($args['id'])) $context->request->merge($args);

        $type = 'normal';
        if(isset($args['type'])) $type = $args['type'];

        $fn = $args['method'];
        try{
            return $this->{$fn}($context->request)->where('type',$type);
        }catch (\Exception $e){
            return $e;
        }
    }
}
