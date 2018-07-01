<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 1/07/18
 * Time: 3:35 PM
 */

namespace App\Service;


class GymService extends CRUDService
{

//    use HandlesRoles;

    public function __constructor(){
        $this->picPath = "gymPics";
        $this->picType = "gym";
    }

    public function getModelType()
    {
        return 'App\Models\Gym';
    }

    public function getEventChannel()
    {
        return 'gym';
    }
}