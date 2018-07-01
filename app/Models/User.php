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
        'type',
        'firstname',
        'lastname',
        'bio',
        'email',
        'password',
        'tel',
        'age',
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
                'type',
                'firstname',
                'lastname',
                'age',
                'bio',
                'email',
                'password',
                'tel',
            ],

            /**
             * Foreign Keys in the model.
             */
            'foreign_keys' => false,

            /**
             * Models authorized to modify this model
             */
            'owner' => [
                'id' => 'from_request',
            ]
        ];
    }

    //Gym
    public function owned_gym(){
        return $this->hasOne('App\Models\Gym', 'owner_id');
    }

    //location
    public function location(){
        return $this->morphOne('App\Models\Location', 'locatable');
    }

    //pictures
    public function pictures(){
        return $this->morphMany('App\Models\Picture','picturable');
    }

    //tags
    public function tags(){
        return $this->morphToMany('App\Models\Tag', 'taggable','tagged_95319');
    }

    //reviews
    public function reviews(){
        return $this->morphMany('App\Models\Review', 'reviewable');
    }

    //ratings
    public function ratings(){
        return $this->morphMany('App\Models\Rate', 'ratable');
    }

    //Average rating (max: 5 stars)
    public function rating(){
        $sum = 0; $count = 0;
        foreach ($this->ratings() as $rating){
            $sum += $rating->stars;
            $count++;
        }
        return $sum/$count;
    }

    //Number of users who rated
    public function rating_user_count(){
        //TODO: better way to count ratings
        $count = 0;
        foreach ($this->ratings() as $rating){
            $count++;
        }
        return $count;
    }
}
