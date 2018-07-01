<?php

namespace App\Http\Controllers;

use App\Traits\DoesResponses;
use Illuminate\Http\Request;

class TempController extends Controller
{
    use DoesResponses;

    public function storeTempPic(Request $request){
        $path = 'public/temp';
        if($request->hasFile('tempImage')){

            $fileName = explode('.',$request->file('tempImage')->getClientOriginalName());
            $extension = $request->file('tempImage')->getClientOriginalExtension();
            $size = $request->file('tempImage')->getSize();

            //filename to store
            $fileNameToStore = $fileName[0] . '_' . time() .'.'. $extension;

            $request->file('tempImage')->storeAs($path,$fileNameToStore);

            return $this->successResponse([
                'remote_location' => $path . '/' .$fileNameToStore,
                'size' => $size,
                'type' => $extension
            ]);
        }else{
            return $this->errorResponse(
                ['storeTempPic' => 'No image sent. Please make sure to name your image as "tempImage"'],
                null,
                400
            );
        }

    }

    public function storeTempDoc(Request $request){
        $path = 'public/temp';

        $fileName = explode('.',$request->file('tempDoc')->getClientOriginalName());
        $extension = $request->file('tempDoc')->getClientOriginalExtension();
        $size = $request->file('tempDoc')->getClientSize();

        //filename to store
        $fileNameToStore = $fileName[0] . '_' . time() .'.'. $extension;

        $request->file('tempDoc')->storeAs($path,$fileNameToStore);

        return $this->successResponse([
            'remote_location'=>$path . '/' .$fileNameToStore,
            'size' => $size,
            'type' => $extension
        ]);
    }
}
