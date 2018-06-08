<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 9/30/17
 * Time: 2:13 PM
 */

namespace App\Util\CRUD;

use App\Traits\DoesResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

trait HandlesCRUDRequest
{
    use DoesResponses;

    /**
     * @var $CRUDService \App\Util\CRUD\HandlesCRUD
     * */
    protected $CRUDService;

    //TODO: make dynamic adding and updating validation rules
    protected $addValidationRules;
    protected $updateValidationRules;


    /**
     * Fetch all Models without any restriction.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @internal param $id
     */
    public function getAll(Request $request){
        if($this->CRUDService->getAll($request)){
            return $this->successResponse($this->CRUDService->data);
        }else{
            return $this->errorResponse($this->CRUDService->errors,$this->CRUDService->data);
        }
    }

    /**
     * Fetch a Model without any restriction.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request,$id){
        if($this->CRUDService->get($request,$id)){
            return $this->successResponse($this->CRUDService->data,$this->CRUDService->status);
        }else{
            return $this->errorResponse($this->CRUDService->errors,$this->CRUDService->data,$this->CRUDService->status);
        }
    }

    /**
     * Store a newly created Model in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request){
        $this->validate($request,$this->addValidationRules);

        if($this->CRUDService->add($request)){
            return $this->successResponse($this->CRUDService->data,$this->CRUDService->status);
        }else{
            return $this->errorResponse($this->CRUDService->errors,$this->CRUDService->data,$this->CRUDService->status);
        }
    }

    /**
     * Update the specified Model in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,$this->updateValidationRules);

        if($this->CRUDService->update($request,$id)){
            return $this->successResponse($this->CRUDService->data,$this->CRUDService->status);
        }else{
            return $this->errorResponse($this->CRUDService->errors,$this->CRUDService->data,$this->CRUDService->status);
        }
    }

    /**
     * Remove the specified Model from storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request,$id){
        if($this->CRUDService->delete($request, $id)){
            return $this->successResponse($this->CRUDService->data,$this->CRUDService->status);
        }else{
            return $this->errorResponse($this->CRUDService->errors,$this->CRUDService->data,$this->CRUDService->status);
        }
    }



}