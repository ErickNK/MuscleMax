<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 19/06/18
 * Time: 10:49 AM
 */

namespace App\Service;

use App\Models\Gym;
use App\Models\Gym_Membership;
use App\Util\CRUD\HandlesImages;
use Illuminate\Http\Request;

class GymMembershipService extends Service
{

    public function joinGym(Request $request){
        //TODO: gym application process
        try {
            $this->data = Gym_Membership::create([
                'gym_id' => $request->gym_id,
                'user_id' => $request->user_id,
                'join_status' => "JOINED"
            ]);
            $this->status = 200;
            return empty($this->errors);
        } catch (\Exception $e) {
            $this->errors['join_group'] = $e->getMessage();
            $this->status = 500;
            return empty($this->errors);
        }
    }

    public function leaveGym(Request $request){
        //TODO: gym leaving process
        try {
            if ($model = Gym_Membership::where([
                ['user_id','=',$request->user_id],
                ['gym_id','=',$request->gym_id]
            ])->first()){
                if ($model->delete()){
                    $model->join_status = "LEFT";
                    $this->data = $model;
                    $this->status = 200;
                    return empty($this->errors);
                }else{
                    $this->errors['leave_gym'] = "delete_failed";
                    $this->status = 500;
                    return empty($this->errors);
                }

            }else{
                $this->errors['leave_gym'] = "not_found";
                $this->status = 404;
                return empty($this->errors);
            }
        } catch (\Exception $e) {
            $this->errors['leave_gym'] = $e->getMessage();
            $this->status = 500;
            return empty($this->errors);
        }
        //TODO: inform others.
    }
}