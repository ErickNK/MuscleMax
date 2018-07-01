<?php

namespace App\Models;

use App\Util\CRUD\CRUDable;
use Illuminate\Database\Eloquent\Model;
use Sleimanx2\Plastic\Searchable;

class Gym extends Model implements CRUDable
{

    use Searchable;

    protected $table = "gyms_95319";

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'owner_id',

        'name',
        'helpline'
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
                'helpline'
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

    //#################### SEARCHING ###################//

    public $documentType = 'gym';

    public static function index(){
        foreach (static::all() as $model){
            $model->document()->save();
        }
        return true;
    }

    //#################### RELATIONSHIP ###################//

    //owner
    public function owner(){
        return $this->belongsTo('App\Models\User','owner_id');
    }

    //location
    public function location(){
        return $this->morphOne('App\Models\Location', 'locatable');
    }

    //Gym_Memberships
    public function gym_memberships(){
        return $this->hasMany('App\Models\Gym_Membership','gym_id');
    }

    //Gym_Members
    public function gym_members(){
        return $this->belongsToMany('App\Models\User','gym_memberships_95319','gym_id');
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
