<?php

namespace App\Service;


class UserService extends CRUDService
{

//    use HandlesRoles;

    public function __constructor(){
        $this->picPath = "userPics";
        $this->picType = "user";
    }

    public function getModelType()
    {
        return 'App\Models\User';
    }

    public function getEventChannel()
    {
        return 'user';
    }
}