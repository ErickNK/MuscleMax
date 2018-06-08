<?php
/**
 * Created by PhpStorm.
 * User: erikn
 * Date: 9/21/2017
 * Time: 10:36 PM
 */

namespace App\Util\RedisMapper;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Redis;

class RedisMapper
{
    /**
     * Map a Model from the Request|User object
     * into the Redis server.
     *
     * @param Request|Model $model
     * @param string $prefix - Prefix to be used as unique key.
     * @param string $id - If null it is assumed it is within the Model itself.
     * @param bool $useId - Determines whether id will be used within unique key.
     * @throws \Exception
     */
    public static function mapModelInRedis($model, $prefix = '', $id = null,$useId = true){
        if($prefix == null && $id == null)
            throw new \Exception('Both prefix and id can\'t be null');

        $id = $id != null? $id: $model->id;

        $prefix = $useId != false ? $prefix . $id: $prefix;

        if($model instanceof Model){
            $model->__set('id',$id);
            Redis::hmset($prefix,$model->attributesToArray());
        }elseif($model instanceof Request){
            $array = $model->all();
            array_set($array,'id',$id);
            Redis::hmset($prefix,$array);
        }
    }
}