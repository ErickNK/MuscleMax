<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 5/28/18
 * Time: 10:08 PM
 */

namespace App\Util\CRUD;


use Exception;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use GraphQL;

trait HandlesGraphQLQueryRequest
{

    /**
     * @var $CRUDService \App\Service\CRUDService
     */
    protected $CRUDService;

    /**
     * @var $modelType string
     */
    protected $modelType;

    /**
     * Fetch a Model without any restriction.
     *
     * @param Request $request
     * @return Model
     * @throws Exception
     */
    public function GET_ONE(Request $request){
        if (!$request->id) {
            if($this->CRUDService->get($request,$request->id)){
                return $this->CRUDService->data['get'];
            }else{
                throw new Exception(json_encode($this->CRUDService->errors));
            }
        }else{
            throw new Exception("Id is required");
        }
    }

    /**
     * Fetch all Models without any restriction.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function GET_ALL(Request $request){
        if($this->CRUDService->getAll($request)){
            return $this->CRUDService->data['get_all'];
        }else{
            throw new Exception(json_encode($this->CRUDService->errors));
        }
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        if(isset($args['id'])) $context->request->merge($args);

        $fn = $args['method'];
        try{
            return $this->{$fn}($context->request);
        }catch (\Exception $e){
            return $e;
        }
    }

    public function search($root, $args, $context, ResolveInfo $info){
        $type = "match"; $property = "first_name"; $term = "";
        if (isset($args['type'])) $type = $args['type'];
        if (isset($args['property'])) $property = $args['property'];
        if (isset($args['term'])) $term = $args['term'];


        $results = call_user_func([$this->modelType,'search'])
            ->{$type}($property,$term)
            ->get();
        if ($results){
            return [
                'took' => $results->took(),
                'totalHits' => $results->totalHits(),
                'maxScore' => $results->maxScore(),
                'aggr' => $results->aggregations(),
                'hits'=>$results->hits(),
            ];
        }
        return null;
    }
}