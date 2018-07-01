<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 5/28/18
 * Time: 10:20 PM
 */

namespace App\Util\CRUD;

use Exception;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

trait HandlesGraphQLMutationRequest
{
    /**
     * @var $CRUDService \App\Service\CRUDService
     */
    protected $CRUDService;

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Model
     * @throws Exception
     */
    public function ADD(Request $request){
        if($this->CRUDService->add($request)){
            return $this->CRUDService->data['added'];
        }else{
            throw new Exception(json_encode($this->CRUDService->errors));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return Model
     * @throws Exception
     */
    public function UPDATE(Request $request,$id = null){
        if($this->CRUDService->update($request,$id)){
            return $this->CRUDService->data['updated'];
        }else{
            throw new Exception(json_encode($this->CRUDService->errors));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param $id
     * @return Model
     * @throws Exception
     */
    public function DELETE(Request $request,$id = null){
        if($this->CRUDService->delete($request,$id)){
            return $this->CRUDService->data['deleted'];
        }else{
            throw new Exception(json_encode($this->CRUDService->errors));
        }
    }
}