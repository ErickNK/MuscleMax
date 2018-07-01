<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{

    protected $table = "gyms_95319";

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'owner_id',
        'name',
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
                'name',
            ],

            /**
             * Foreign Keys in the model.
             */
            'foreign_keys' => [
                'owner_id',
            ],

            /**
             * Models authorized to modify this model
             */
            'owner' => [
                'owner_id' => null,
            ]
        ];
    }

    //#################### RELATIONSHIPS ###################//


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
        return $this->morphToMany('App\Models\Tag', 'taggable','tagged');
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
