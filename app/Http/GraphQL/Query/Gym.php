<?php

namespace App\Http\GraphQL\Query;

use App\Service\GroupService;
use App\Service\GymService;
use App\Util\CRUD\HandlesGraphQLQueryRequest;

class Gym
{
    use HandlesGraphQLQueryRequest;

    public function __construct(GymService $CRUDService)
    {
        $this->CRUDService = $CRUDService;
        $this->modelType = "App\\Models\\Gym";
    }
}
