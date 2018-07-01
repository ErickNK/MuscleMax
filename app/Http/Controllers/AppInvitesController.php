<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 26/06/18
 * Time: 11:45 AM
 */

namespace App\Http\Controllers;



use App\Service\AppInvitesService;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;

class AppInvitesController extends Controller
{
    /**
     * @var $appInvitesService AppInvitesService
     * */
    protected $appInvitesService;

    public function __construct(AppInvitesService $appInvitesService){
        $this->appInvitesService = $appInvitesService;
    }

    public function commitAppInvites(Request $request,Mailer $mailer){
        $this->validate($request,[
            'users' => 'required|array'
        ]);

        if($this->appInvitesService->commitAppInvites($request,$mailer)){
            return $this->successResponse($this->appInvitesService->data,$this->appInvitesService->status);
        }else{
            return $this->errorResponse($this->appInvitesService->errors,$this->appInvitesService->data,$this->appInvitesService->status);
        }
    }


    public function searchForFriends(Request $request){
        $this->validate($request,[
            'users' => 'required|array'
        ]);

        if($this->appInvitesService->searchForFriends($request)){
            return $this->successResponse($this->appInvitesService->data,$this->appInvitesService->status);
        }else{
            return $this->errorResponse($this->appInvitesService->errors,$this->appInvitesService->data,$this->appInvitesService->status);
        }
    }
}