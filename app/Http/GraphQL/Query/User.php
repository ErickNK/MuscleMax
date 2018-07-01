<?php

namespace App\GraphQL\Query;

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


}
