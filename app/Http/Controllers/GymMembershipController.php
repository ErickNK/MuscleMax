<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 23/06/18
 * Time: 11:53 AM
 */

namespace App\Http\Controllers;


use App\Service\GymMembershipService;
use App\Util\CRUD\HandlesCRUDRequest;
use Illuminate\Http\Request;

class GymMembershipController extends Controller
{
    use HandlesCRUDRequest;

    public function __construct(GymMembershipService $CRUDService)
    {
        $this->CRUDService = $CRUDService;
    }

    function joinGym(Request $request){
        $this->validate($request,[
            'user_id' => 'required|integer',
            'gym_id' => 'required|integer'
        ]);

        if($this->CRUDService->joinGym($request)){
            return $this->successResponse($this->CRUDService->data,$this->CRUDService->status);
        }else{
            return $this->errorResponse($this->CRUDService->errors,$this->CRUDService->data,$this->CRUDService->status);
        }
    }

    function leaveGym(Request $request){
        $this->validate($request,[
            'user_id' => 'required|integer',
            'gym_id' => 'required|integer'
        ]);

        if($this->CRUDService->leaveGym($request)){
            return $this->successResponse($this->CRUDService->data,$this->CRUDService->status);
        }else{
            return $this->errorResponse($this->CRUDService->errors,$this->CRUDService->data,$this->CRUDService->status);
        }
    }
}