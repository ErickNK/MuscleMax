<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = "ratings_95319";

    protected $fillable = [
        'ratable_id',
        'ratable_type',
        'user_id',

        'stars',
    ];

    //polymorphic relationship
    public function ratable(){
        return $this->morphTo();
    }
}
