<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagged extends Model
{
    protected $table = 'tagged_95319';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag_id',

        'taggable_id',
        'taggable_type',
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

            ],

            /**
             * Foreign Keys in the model.
             */
            'foreign_keys' => [
                'tag_id',
                'taggable_id',
                'taggable_type',
            ],

            /**
             * Models authorized to modify this model
             */
            'owner' => [
                'owner_id' => null
            ]
        ];
    }
}
