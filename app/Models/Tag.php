<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags_95319';

    protected $fillable = [
        'name',
        'description',
        'color',
    ];

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
