<?php

namespace App\Models;

use App\Util\CRUD\CRUDable;
use Illuminate\Database\Eloquent\Model;

class Weight_Measurement extends Model implements CRUDable
{
    protected $table = "weight_measurements_95319";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_id',

        'weight',
        'height',
        'bmi',
        'type',
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
                'weight',
                'height',
                'bmi',
                'type',
            ],

            /**
             * Foreign Keys in the model.
             */
            'foreign_keys' => [
                'owner_id'
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

    //note
    public function note(){
        return $this->hasOne('App\Models\Note','weight_measurement_id');
    }

    //tags
    public function tags(){
        return $this->morphToMany('App\Models\Tag', 'taggable','tagged_95319');
    }

    //pictures
    public function pictures(){
        return $this->morphMany('App\Models\Picture','picturable');
    }
}
