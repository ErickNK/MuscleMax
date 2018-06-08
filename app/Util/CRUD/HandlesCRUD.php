<?php
/**
 * Created by PhpStorm.
 * User: erikn
 * Date: 9/22/2017
 * Time: 9:04 AM
 */

namespace App\Util\CRUD;

use App\Events\CRUDEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

trait HandlesCRUD
{
    use HandlesImages;

    /**
     * Errors found in processing services.
     *
     * */
    public $errors = [];

    /**
     * Information after processing services.
     *
     * */
    public $data = [];

    /**
     * Status of the service.
     *
     * */
    public $status = 200;

    /**
     * Fetch model's CRUD settings
     *
     * @param $key
     * @return mixed
     */
    private function fromSettings($key){
        return (call_user_func([$this->getModelType(),'crudSettings']))[$key];
    }

    /**
     * Fetch model according to the owner. Returns only one model.
     * Models that are authorized to modify or delete this model are
     *  - User who owns the model.
     *  - User who created the model.
     *  - User with higher authority to do data manipulation. (eg. SYSTEM).
     *
     * The request must contain the owner key in order to modify the model.
     *
     * @param Request $request
     * @param $id
     * @return bool|Model
     */
    private function getModel(Request $request, $id,$mode){
        $model = null;

        //Start query building
        $model = call_user_func_array([$this->getModelType(), 'where'], ['id', '=', $id]);//fetch with models id

        //CHECK FOR EXISTANCE
        $test = $model;
        try {
            if (!$test->first()) {
                $this->errors[$mode] = "The resource with id: " . $id . " could not be found";
                $this->status = 403;
                return false;
            }
        } catch (\Exception $e) {
            $this->errors[$mode] = $e->getMessage();
            $this->status = 500;
            return false;
        }

        //CHECK FOR OWNERSHIP
        foreach ($this->fromSettings('owner') as $key => $value) {
            if ($request->has($key)) {
                $model->where($key, '=', $request->get($key));
            } else if ($value) {
                //for root nodes that have no owner (i.e property type)
                $model->where($key, '=', $value);
            } else {
                $model->where($key, '=', null); //refuse
            }
        }

        //EXECUTE QUERY
        try {
            if (!$model = $model->first()) {
                $this->errors['authorization'] = 'You are not authorized for this operation.';
                $this->status = 403;
                return false;
            }
        } catch (\Exception $e) {
            $this->errors[$mode] = $e->getMessage();
            $this->status = 500;
            return false;
        }

        return $model;
    }

    /**
     * Used to resolve the relationships of the model. This means fetching
     * the relevant ids of the foreign columns. The request must contain the
     * relation keys.
     *
     * @param Request $request
     * @return array|bool
     */
    private function resolveForeignKeys(Request $request){
        $relations = array();
        foreach ($this->fromSettings('foreign_keys') as $key => $value) {

            /**
             * If the request has the related model itself.
             * Get it from the related model
            */
            if ($request->has($key)){
                $relations[$value] = $request->{$key}[$value];
                continue;
            }
            /**
             * If the request has only the foreign key field then
             * get it from there.
            */
            else if ($request->has($value)){
                $relations[$value] = $request->{$value};
                continue;
            }
            /**
             * If there is no field. We cannot persist this to the database.
            */
            else {
                $this->errors['add'] = "Foreign key ". $value . " must be resolved.";
                $this->status = 500;
            }
        }
        return empty($this->errors) ? $relations: false;
    }

    /**
     * Encrypt any attribute that has password as key.
     * @param $attributes
     */
    private function handlePassword(&$attributes){
        $attributes['password'] = bcrypt($attributes['password']);
    }

    /**
     * Add a Model.
     *
     * @param Request $request
     * @internal Model $model
     * @return bool
     */
    public function add(Request $request){
        $model = null;

        if ($this->fromSettings('foreign_keys')) //If the relation has foreign keys
            //Check for correct attributes and foreign keys
            if($relations = $this->resolveForeignKeys($request)){
                $attributes = array_merge(
                    $request->only($this->fromSettings('attributes')),
                    $relations
                );

            }else return false;
        else $attributes = $request->only($this->fromSettings('attributes'));

        //handle passwords
        if (array_key_exists('password',$attributes)){
            $this->handlePassword($attributes);
        }

        //Add hook before creating
        if(!$this->beforeCreate($request,$attributes))
            return empty($this->errors);

        //ADD
        //TODO: catch any queries Errors
        try {
            if ($model = call_user_func([$this->getModelType(), 'create'], $attributes)) {
                $this->data = $model;
                $this->status = 200;
            } else {
                $this->errors['add'] = "Adding resource with id: " . $model->id . "failed";
                $this->status = 500;
            }
        } catch (\Exception $e) {
            $this->errors['add'] = $e->getMessage();
            $this->status = 500;
        }

        //Add hook after creating
        if(!$this->afterCreate($request,$model))
            return empty($this->errors);

        return empty($this->errors);
    }

    /**
     * Update a Model
     *
     * @param Request $request
     * @param $id
     * @return bool
     */
    public function update(Request $request,$id){
        //Add hook before update
        if(!$this->beforeUpdate($request,$id))
            return empty($this->errors);

        //UPDATE
        if($model = $this->getModel($request,$id,"update")) {
            if ($model->update($request->only($this->fromSettings('attributes')))) {
                $this->data = $model;
                $this->status = 200;
            } else {
                $this->errors['delete'] = "Deleting resource with id: " . $id . "failed";
                $this->status = 500;
            }

            //Add hook after update
            if(!$this->afterUpdate($request,$model))
                return empty($this->errors);
        }

        return empty($this->errors);
    }

    /**
     * Delete a Model
     *
     * @param Request $request
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function delete(Request $request,$id){
        $model = null;

        //Add hook before delete
        if(!$this->beforeDelete($request,$id))
            return empty($this->errors);

        //DELETE
        if($model = $this->getModel($request,$id,"delete")) {
            if ($model->delete()) {
                $this->data = $model;
                $this->status = 200;
            } else {
                $this->errors['delete'] = "Deleting resource with id: " . $id . "failed";
                $this->status = 500;
            }

            //Add hook after creating
            if(!$this->afterDelete($request,$model))
                return empty($this->errors);
        }

        return empty($this->errors);
    }

    /**
     * Fetch all Models without any restriction.
     *
     * @param Request $request
     * @return bool
     * @internal param $id
     */
    public function getAll(Request $request){
        $models = [];

        //Add hook before get_all
        if(!$this->beforeGetAll($request))
            return empty($this->errors);

        //GET ALL
        try {
            $this->data = $models = call_user_func([$this->getModelType(), 'all']);
            $this->status = 200;
        } catch (\Exception $e) {
            $this->errors['get_all'] = $e->getMessage();
            $this->status = 500;
        }

        //Add hook after update
        if(!$this->afterGetAll($request,$models))
            return empty($this->errors);

        return empty($this->errors);
    }

    /**
     * Fetch a Model without any restriction.
     *
     * @param Request $request
     * @param $id
     * @return bool
     */
    public function get(Request $request, $id){
        $model = null;

        //Add hook before get_all
        if(!$this->beforeGet($request))
            return empty($this->errors);

        //GET
        try {
            if ($model = call_user_func_array([$this->getModelType(), 'where'], ['id', '=', $id])->get()->first()) {
                $this->data = $model;
                $this->status = 200;
            } else {
                $this->errors['get'] = "not_found";
                $this->status = 404;
            }
        } catch (\Exception $e) {
            $this->errors['get'] = $e->getMessage();
            $this->status = 500;
        }

        //Add hook after update
        if(!$this->afterGet($request,$model))
            return empty($this->errors);

        return empty($this->errors);
    }

    //HOOKS

    public function beforeCreate($request,$attributes){
        //
        return true;
    }

    public function afterCreate($request,$model){
        $crudEvent = new CRUDEvent();
        $crudEvent->setPayload([
            'crudType' => 'created',
            'channel' => $this->getEventChannel(),
            'model' => $model
        ]);
        broadcast($crudEvent)->toOthers();
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
