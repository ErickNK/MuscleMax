<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{

    protected $table = "pictures_95319";

    protected $fillable = [
        'picturable_id',
        'picturable_type',

        'name',
        'type',
        'size',
        'remote_location',
        'description',
    ];

    /**
     * Retrieve the model(picturable model) that owns this picture.
     */
    //polymorphic relationship
    public function picturable(){
        return $this->morphTo();
    }
}
