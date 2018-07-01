<?php

namespace App\Models;

use App\Util\CRUD\CRUDable;
use App\Util\CRUD\CRUDService;
use Illuminate\Database\Eloquent\Model;

class Note extends Model implements CRUDable
{
    protected $table = "notes_95319";

    protected $fillable = [
        'owner_id',
        'weight_measurement_id',

        'content',
    ];

    //CRUD
    public static function crudSettings()
    {
        return[
            /**
             * Attributes that will be persisted to and from the
             * database
             */
            'attributes' => [
                'content',
            ],

            /**
             * Foreign Keys in the model.
             */
            'foreign_keys' => [
                'owner_id',
                'weight_measurement_id',
            ],

            /**
             * Models authorized to modify this model
             */
            'owner' => [
                'owner_id' => 'from_request',
            ]
        ];
    }

    //owner
    public function owner(){
        return $this->belongsTo('App\Models\User','owner_id');
    }

    //weight measurement
    public function weight_measurement(){
        return $this->belongsTo('App\Models\Weight_Measurement','weight_measurement_id');
    }
}
