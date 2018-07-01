<?php

namespace App\GraphQL\Mutation;

use App\Service\GymService;
use App\Util\CRUD\HandlesGraphQLMutationRequest;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;

class Gym
{
    use HandlesGraphQLMutationRequest;

    public function __construct(GymService $CRUDService)
    {
        $this->CRUDService = $CRUDService;
    }


    /**
     * Resolve the mutation.
     *
     * @param  mixed $root
     * @param  array $args
     * @return mixed
     * @throws \Exception
     */
    public function resolve($root, $args, $context, ResolveInfo $info)
    {

        if(isset($args['pictures'])){
            $context->request->request->add(['with_temp_pics' => true]);
        }

        $context->request->merge(array_merge($args['gym'],$args));

        $fn = $args['method'];
        return $this->$fn($context->request,$context->request->id ?? $context->request->id);
    }
}
