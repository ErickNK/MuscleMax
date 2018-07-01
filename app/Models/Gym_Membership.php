<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gym_Membership extends Model
{
    protected $table = "gym_memberships_95319";

    protected $fillable = [
        'gym_id',
        'user_id',
        'join_status'
    ];

    //user
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    //gym
    public function gym(){
        return $this->belongsTo('App\Models\Gym','gym_id');
    }
}
