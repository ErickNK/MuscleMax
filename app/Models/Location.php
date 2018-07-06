<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sleimanx2\Plastic\Searchable;

class Location extends Model
{
    use Searchable;

    protected $table = "locations_95319";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address',
        'type',
        'latLng',

        'locatable_id',
        'locatable_type',
    ];

    //#################### SEARCHING ###################//

    public $documentType = 'location';

    public static function index(){
        foreach (static::all() as $model){
            $model->document()->save();
        }
        return true;
    }

    //#################### RELATIONSHIP ###################//

    /**
     * Retrieve the model(locatable model) that owns this location.
     */
    //polymorphic relationship
    public function locatable(){
        return $this->morphTo();
    }
}
