<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sleimanx2\Plastic\Searchable;

class Tag extends Model
{

    use Searchable;

    protected $table = 'tags_95319';

    protected $fillable = [
        'name',
        'description',
        'color',
    ];

    //#################### SEARCHING ###################//

    public $documentType = 'tag';

    public static function index(){
        foreach (static::all() as $model){
            $model->document()->save();
        }
        return true;
    }

    //#################### RELATIONSHIP ###################//

//RELATIONSHIPS
    //Meetings
    public function gyms()
    {
        return $this->morphedByMany('App\Model\Gym', 'taggable','tagged_95319');
    }

    //Events
    public function coaches()
    {
        return $this->morphedByMany('App\Model\User', 'taggable','tagged_95319');
    }
}
