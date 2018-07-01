<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    protected $table = "reviews_95319";

    protected $fillable = [
        'reviewable_id',
        'reviewable_type',

        'content',
    ];
    //polymorphic relationship
    public function reviewable(){
        return $this->morphTo();
    }
}
