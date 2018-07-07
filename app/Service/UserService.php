<?php

namespace App\Service;


use App\Models\Weight_Measurement;

class UserService extends CRUDService
{

//    use HandlesRoles;

    protected $picPath = "userPics";

    protected $picType = "user";

    public function getModelType()
    {
        return 'App\Models\User';
    }

    public function getEventChannel()
    {
        return 'user';
    }

    public function getPolymorphicName(){
        return "user";
    }

    public function afterCreate($request, $model)
    {
        parent::afterCreate($request, $model);

        try{
            //INITIAL WEIGHT MEASUREMENT
            if($request->has("with_weight_measurement")){
                $this->data['pictures'] = Weight_Measurement::create([
                    'owner_id' => $model->id,
                    'weight' => $request->weight_measurement['weight'],
                    'height' => $request->weight_measurement['height'],
                    'bmi' => $request->weight_measurement['bmi'],
                    'type' => 'initial',
                ]);
            }
        }catch (\Exception $e){
            $this->errors['add'] = $e->getMessage();
            $this->status = 500;
        }
        return empty($this->errors);
    }
}