<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    protected $table = "reviews_95319";

    protected $fillable = [
        'owner_id',

        'reviewable_id',
        'reviewable_type',

        'content',
    ];

    //#################### CRUD ###################//

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
                'reviewable_id',
                'reviewable_type',
            ],

            /**
             * Models authorized to modify this model
             */
            'owner' => [
                'owner_id' => null,
            ]
        ];
    }
    //polymorphic relationship
    public function reviewable(){
        return $this->morphTo();
    }

    public function owner(){
        return $this->belongsTo("App\Models\User","owner_id");
    }
}
