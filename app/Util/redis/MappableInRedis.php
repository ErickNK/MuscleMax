<?php
/**
 * Created by PhpStorm.
 * User: erikn
 * Date: 9/21/2017
 * Time: 11:24 PM
 */

namespace App\Util\RedisMapper;


interface MappableInRedis
{
    public function getAttributeMap();
}