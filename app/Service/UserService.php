<?php

namespace App\Service;

use App\Util\CRUD\CRUDService;
use App\Util\CRUD\HandlesCRUD;
use App\Util\CRUD\HandlesImages;


class UserService implements CRUDService
{
    use HandlesCRUD, handlesImages;

    public function __constructor(){
        $this->picPath = "userPics";
        $this->picType = "userPic";
    }

    public function getModelType()
    {
        return 'App\Models\User';
    }

    public function getEventChannel()
    {
        return 'user';
    }

    public function afterCreate($request,$model)
    {
//        parent::afterCreate($request,$model);

        //TODO: make Transactable. Should be atomic if it fails
        //IF THE USER HAS TEMPORARY PICTURES SAVE THEM
        if($request->has("with_temp_pics")){
            $pics = [];
            foreach ($request->pictures as $picture){
                $pics[] = $this->saveImagesFromTemp($picture,$model->id);
            }
            $this->data["pictures"] = $pics;
        }

        return true;
    }
}