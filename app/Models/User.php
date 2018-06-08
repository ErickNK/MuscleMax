<?php

namespace App\Models;

use App\Util\CRUD\CRUDable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements CRUDable
{
    use Notifiable;


    protected $table = "users_95319";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
                'firstname',
                'lastname',
                'email',
                'password',
            ],

            /**
             * Foreign Keys in the model.
             */
            'foreign_keys' => false,

            /**
             * Models authorized to modify this model
             */
            'owner' => [
                'id' => null,
            ]
        ];
    }

    public function pictures(){
        return $this->morphMany('App\Models\Picture','picturable');
    }
}
