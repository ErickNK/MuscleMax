<?php

use Sleimanx2\Plastic\Facades\Map;
use Sleimanx2\Plastic\Map\Blueprint;
use Sleimanx2\Plastic\Mappings\Mapping;

class LocationMapping extends Mapping
{
    /**
     * Full name of the model that should be mapped
     *
     * @var string
     */
    protected $model = \App\Models\Location::class;

    /**
     * Run the mapping.
     *
     * @return void
     */
    public function map()
    {
        Map::create($this->getModelType(),function(Blueprint $map){
            $map->point('latLng',['store'=>'true','index'=>'analyzed','analyzer' => 'standard',]);
            $map->string('country',['store'=>'true','index'=>'analyzed','analyzer' => 'standard',]);
            $map->string('county',['store'=>'true','index'=>'analyzed','analyzer' => 'standard',]);
            $map->string('city',['store'=>'true','index'=>'analyzed','analyzer' => 'standard',]);
            $map->string('street',['store'=>'true','index'=>'analyzed','analyzer' => 'standard',]);
            $map->string('building',['store'=>'true','index'=>'analyzed','analyzer' => 'standard',]);

        },$this->getModelIndex());
    }
}
