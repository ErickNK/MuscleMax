<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 1/07/18
 * Time: 2:10 PM
 */

namespace App\Service;

use App\Events\CRUDEvent;
use App\Util\CRUD\HandlesCRUD;
use App\Util\CRUD\HandlesImages;
use App\Util\CRUD\HandlesRoles;

class CRUDService extends Service {

    use HandlesCRUD,HandlesImages;

    /**
     * Path used to fetch the picture from its location in the
     * public folder. NB: the path should not include 'public/'
     * as this is taken care of by the symbolic link setup for the storage.
     *
     * @var string
     */
    protected $picPath;

    /**
     * Type used to relate the Picture entity with another
     * entity.
     *
     * @var string
     */
    protected $picType;


    public function getModelType(){
        //
    }

    public function getEventChannel(){
        //
    }

    //HOOKS

    public function beforeCreate($request,$attributes){
        //
        return true;
    }

    public function afterCreate($request,$model){
        //SEND ANY CRUD EVENT
        $crudEvent = new CRUDEvent();
        $crudEvent->setPayload([
            'crudType' => 'created',
            'channel' => $this->getEventChannel(),
            'model' => $model
        ]);
        broadcast($crudEvent)->toOthers();

        //SAVE ANY TEMPORARY IMAGE
        if($request->has("with_temp_pics")){
            $pics = [];
            foreach ($request->pictures as $picture){
                $pics[] = $this->saveImagesFromTemp($picture,$model->id);
            }
            $this->data['pictures'] = $pics;
        }
        return true;
    }

    public function beforeUpdate($request,$attributes){
        //
        return true;
    }

    public function afterUpdate($request,$model){
        $crudEvent = new CRUDEvent();
        $crudEvent->setPayload([
            'crudType' => 'updated',
            'channel' => $this->getEventChannel(),
            'model' => $model
        ]);
        broadcast($crudEvent)->toOthers();
        return true;
    }

    public function beforeDelete($request,$attributes){
        //
        return true;
    }

    public function afterDelete($request,$model){
        $crudEvent = new CRUDEvent();
        $crudEvent->setPayload([
            'crudType' => 'deleted',
            'channel' => $this->getEventChannel(),
            'model' => $model
        ]);
        broadcast($crudEvent)->toOthers();
        return true;
    }

    public function beforeGetAll($request){
        //
        return true;
    }

    public function afterGetAll($request,$models){
        //
        return true;
    }

    public function beforeGet($request){
        //
        return true;
    }

    public function afterGet($request,$model){
        //
        return true;
    }
}