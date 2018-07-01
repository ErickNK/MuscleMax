<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 1/07/18
 * Time: 10:06 PM
 */

namespace App\Http\GraphQL\Query;


use App\Models\Gym;
use App\Util\CRUD\HandlesGraphQLQueryRequest;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;
use Sleimanx2\Plastic\Facades\Plastic;
use Sleimanx2\Plastic\Fillers\EloquentFiller;
use Sleimanx2\Plastic\PlasticResult;

class Location
{

    use HandlesGraphQLQueryRequest;


    public function __construct()
    {
        $this->modelType = "App\\Models\\Location";
    }

    public function singleSearch($root, $args, $context, ResolveInfo $info){
        $type = "match"; $property = "firstname"; $term = "";
        if (isset($args['type'])) $type = $args['type'];
        if (isset($args['property'])) $property = $args['property'];
        if (isset($args['term'])) $term = $args['term'];


        $results = call_user_func([$this->modelType,'search'])
            ->{$type}($property,$term)
            ->get();

        $hits = array();

        foreach ($results->hits()->where('type','gym_location') as $h){
            /** @var \App\Models\Location $h */
            $hits[] = $h->locatable();
        }

        if ($results){
            return [
                'took' => $results->took(),
                'totalHits' => $results->totalHits(),
                'maxScore' => $results->maxScore(),
                'aggr' => $results->aggregations(),
                'hits'=>$hits,
            ];
        }
        return null;
    }

    public function complexSearch($root, $args, $context, ResolveInfo $info){
        $raw = null;

        if (isset($args['raw'])) $raw = $args['raw'];
        else return new Exception("raw query must be provided");

        $results = Plastic::getClient()->search(json_decode($raw));
        $results = new PlasticResult($results);
        $filler = new EloquentFiller();

        $filler->fill(${$this->modelType}(), $results);

        $hits = array();

        foreach ($results->hits()->where('type','gym_location') as $h){
            /** @var \App\Models\Location $h */
            $hits[] = $h->locatable();
        }

        if ($results){
            return [
                'took' => $results->took(),
                'totalHits' => $results->totalHits(),
                'maxScore' => $results->maxScore(),
                'aggr' => $results->aggregations(),
                'hits'=>$hits
            ];
        }
        return null;
    }

}