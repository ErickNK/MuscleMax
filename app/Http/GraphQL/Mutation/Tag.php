<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 4/07/18
 * Time: 11:05 AM
 */

namespace App\Http\GraphQL\Mutation;


use App\Service\TagService;
use App\Util\CRUD\HandlesGraphQLMutationRequest;
use GraphQL\Type\Definition\ResolveInfo;

class Tag
{
    use HandlesGraphQLMutationRequest;

    public function __construct(TagService $CRUDService)
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

        $context->request->merge(array_merge($args['tag'],$args));

        $fn = $args['method'];
        return $this->$fn($context->request,$context->request->id ?? $context->request->id);
    }

    public function tag($root, $args, $context, ResolveInfo $info)
    {

        $context->request->merge(array_merge($args['tagged'],$args));

        $fn = $args['method'];
        return $this->$fn($context->request,$context->request->id ?? $context->request->id);
    }
}