<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 4/07/18
 * Time: 10:55 AM
 */

namespace App\Http\GraphQL\Mutation;


use App\Service\ReviewService;
use App\Util\CRUD\HandlesGraphQLMutationRequest;
use GraphQL\Type\Definition\ResolveInfo;

class Review
{
    use HandlesGraphQLMutationRequest;

    public function __construct(ReviewService $CRUDService)
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

        $context->request->merge(array_merge($args['review'],$args));

        $fn = $args['method'];
        return $this->$fn($context->request,$context->request->id ?? $context->request->id);
    }
}