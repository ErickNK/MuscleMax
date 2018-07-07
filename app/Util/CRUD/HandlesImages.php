<?php

namespace App\Util\CRUD;


use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HandlesImages
{

    public function getPicType()
    {
        return property_exists($this, 'picType') ? $this->picType : 'defaultPic';
    }

    public function getPicPath()
    {
        return property_exists($this, 'picPath') ? $this->picPath : 'pictures';
    }

    /**
     * Path used to store temporary pictures
     *
     * @var string
     */
    protected $tempPath = 'public/temp';

    /**
     * Store images uploaded through post in the request
     * in the public folder according to the path specified, the
     * store the location in the database using the Picture entity.
     *
     * @param Request $request - request containing the file to store.
     * @param int $id - Id of the model to associate the image with using the picType specified.
    */
    public function handleImage(Request $request,$id){
        $fileName = explode('.',$request->file($this->getPicType())->getClientOriginalName());
        $extension = $request->file($this->getPicType())->getClientOriginalExtension();

        //filename to store
        $fileNameToStore = $fileName[0] . '_' . time() .'.'. $extension;

        //Store in public folder
        $request->file($this->getPicType())->storeAs('public/' . $this->getPicPath(),$fileNameToStore);

        $path = $this->getPicPath() . '/' . $fileNameToStore;

        Picture::create([
            'name'=>$request->get('name'),
            'description'=>$request->get('description'),
            'local_location' => $path,
            'remote_location' => env("APP_URL"). "/storage/" . $this->getPicPath() . "/" . $fileNameToStore,
            'picturable_id' => $id,
            'picturable_type' => $this->getPicType()
        ]);
    }

    /**
     * Store the default image's location into the database using the
     * Picture entity according to the picType specified.
     *
     * @param int $id - Id of the model to associate the image with using the picType specified.
     */
    public function defaultImage($id){
        Picture::create([
            'name' => null,
            'description' => null,
            'remote_location' => env("APP_URL"). "/storage/" . $this->getPicPath() . "/image_placeholder.jpg",
            'picturable_id' => $id,
            'picturable_type' => $this->getPicType()
        ]);
    }

    /**
     * Move image that was in the temporary public location into
     * the specified location and store location into the database
     * using the Picture entity.
     *
     * @param Request $request - request carrying current location of image in the temp folder.
     * @param int $id - Id of the model to associate the image with using the picType specified.
     * @return bool - return true if successful, otherwise false
     */
    public function saveImageFromTemp(Request $request,$id){
        //TODO: add errors
        $fileName = explode('/',$request->image);
        $path = $this->getPicPath() .'/'. end($fileName);

        //Store in public folder
        if ( Storage::move($request->image, 'public/'. $path) &&

        Picture::create([
            'name'=>$request->get('name'),
            'description'=>$request->get('description'),
            'local_location' => $path,
            'remote_location' => env("APP_URL"). "/storage/" . $this->getPicPath() . "/" . end($fileName),
            'type' => $request->get('type'),
            'size' => $request->get('size'),
            'picturable_id' => $id,
            'picturable_type' => $this->getPicType()
        ]))
            return true;
        else return false;
    }

    public function saveImagesFromTemp(array $picture,$id){
        //TODO: add errors
        $fileName = explode('/',$picture['remote_location']);
        $path = $this->getPicPath() .'/'. end($fileName);

        if (Storage::move($picture['remote_location'], 'public/'. $path) &&

        $picture = Picture::create([
            'name'=>$picture['name'],
            'description'=>$picture['description'],
            'local_location' => $path,
            'remote_location' => env("APP_URL"). "/storage/" . $this->getPicPath() . "/" . end($fileName),
            'type' => $picture['type'],
            'size' => $picture['size'],
            'picturable_id' => $id,
            'picturable_type' => $this->getPicType()
        ]))
            return $picture;
        else return false;
    }

    //TODO: implement a job that deletes temp images that overstay by some period of time.

    /**
     * Store an image in the temporary folder for later processing.
     *
     * @param Request $request
     * @return array - array containing the location of the temporary stored image
     */
    public function saveImageToTemp(Request $request){
        $fileName = explode('.',$request->file('tempImage')->getClientOriginalName());
        $extension = $request->file('tempImage')->getClientOriginalExtension();

        //filename to store
        $fileNameToStore = $fileName[0] . '_' . time() .'.'. $extension;

        $request->file('tempImage')->storeAs($this->tempPath,$fileNameToStore);

        return ['location'=>$this->tempPath . '/' .$fileNameToStore];
    }

}