<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sleimanx2\Plastic\Facades\Plastic;
use Sleimanx2\Plastic\Fillers\EloquentFiller;
use Sleimanx2\Plastic\PlasticResult;

class SearchController extends Controller
{
    /**
     * Index the entities in storage for searching.
     *
     * @param $entity
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($entity){
        if (call_user_func(['App\\Models\\' . ucfirst($entity) ,'index'])){
            return $this->successResponse();
        }
        return $this->errorResponse();
    }

    /**
     * Do a complex search on the entity
     *
     * @param $entity
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function complex($entity,Request $request){
        $results = Plastic::getClient()->search($request->raw);
        $results = new PlasticResult($results);
        $filler = new EloquentFiller();

        $model = 'App\\Models\\' . ucfirst($entity);
        $filler->fill(new $model(), $results);
        if ($results){
            return $this->successResponse([
                'took' => $results->took(),
                'totalHits' => $results->totalHits(),
                'maxScore' => $results->maxScore(),
                'aggr' => $results->aggregations(),
                'hits'=>$results->hits(),
            ]);
        }
        return $this->errorResponse();
    }

    /**
     * Do a simple search on the entity
     *
     * @param $entity
     * @param $property
     * @param $term
     * @return \Illuminate\Http\JsonResponse
     */
    public function simple($entity,$property,$term){
        $results = call_user_func(['App\\Models\\' . ucfirst($entity),'search'])
            ->match($property,$term)
            ->get();
        if ($results){
            return $this->successResponse([
                'took' => $results->took(),
                'totalHits' => $results->totalHits(),
                'maxScore' => $results->maxScore(),
                'aggr' => $results->aggregations(),
                'hits'=>$results->hits(),
            ]);
        }
        return $this->errorResponse();
    }
}
