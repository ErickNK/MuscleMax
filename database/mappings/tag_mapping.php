<?php

use Sleimanx2\Plastic\Facades\Map;
use Sleimanx2\Plastic\Map\Blueprint;
use Sleimanx2\Plastic\Mappings\Mapping;

class TagMapping extends Mapping
{
    /**
     * Full name of the model that should be mapped
     *
     * @var string
     */
    protected $model = \App\Models\Tag::class;

    /**
     * Run the mapping.
     *
     * @return void
     */
    public function map()
    {
        Map::create($this->getModelType(),function(Blueprint $map){
            $map->string('name',['store'=>'true','index'=>'analyzed','analyzer' => 'standard',]);
        },$this->getModelIndex());
    }
}
