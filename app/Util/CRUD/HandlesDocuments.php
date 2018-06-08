<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 5/7/18
 * Time: 12:34 AM
 */

namespace App\Util\CRUD;


use App\Models\Document;
use Illuminate\Support\Facades\Storage;

trait HandlesDocuments
{
    /**
     * Type used to relate the Picture entity with another
     * entity.
     *
     * @var string
     */
    protected $docType = 'defaultPic';

    /**
     * Path used to fetch the picture from its location in the
     * public folder. NB: the path should not include 'public/'
     * as this is taken care of by the symbolic link setup for the storage.
     *
     * @var string
     */
    protected $docPath = 'documents';

    /**
     * Store images uploaded through post in the request
     * in the public folder according to the path specified, the
     * store the location in the database using the Picture entity.
     *
     * @param Request $request - request containing the file to store.
     * @param int $id - Id of the model to associate the image with using the picType specified.
     */
    public function handleImage(Request $request,$id){
        $fileName = explode('.',$request->file($this->docType)->getClientOriginalName());
        $extension = $request->file($this->docType)->getClientOriginalExtension();

        //filename to store
        $fileNameToStore = $fileName[0] . '_' . time() .'.'. $extension;

        //Store in public folder
        $request->file($this->docType)->storeAs('public/' . $this->docPath,$fileNameToStore);

        $path = $this->docPath . '/' . $fileNameToStore;

        Document::create([
            'name'=>$request->get('name'),
            'description'=>$request->get('description'),
            'location' => $path,
            'documentable_id' => $id,
            'documentable_type' => $this->docType
        ]);
    }

    /**
     * Store the default image's location into the database using the
     * Picture entity according to the picType specified.
     *
     * @param int $id - Id of the model to associate the image with using the picType specified.
     */
    public function defaultImage($id){
        Document::create([
//            'name'=>$request->get('name'),
//            'description'=>$request->get('description'),
            'location' => $this->docPath .'/'. 'placeHolder.jpg',
            'documentable_id' => $id,
            'documentable_type' => $this->docType
        ]);
    }

    /**
     * Move image that was in the temporary public location into
     * the specified location and store location into the database
     * using the Picture entity.
     *
     * @param Request $request - request carrying current location of image in the temp folder.
     * @param int $id - Id of the model to associate the image with using the picType specified.
     */
    public function saveImageFromTemp(Request $request,$id){
        //TODO: add errors
        $fileName = explode('/',$request->image);
        $path = $this->docPath .'/'. end($fileName);

        //Store in public folder
        Storage::move($request->image, 'public/'. $path);

        Document::create([
            'name'=>$request->get('name'),
            'description'=>$request->get('description'),
            'location' => $path,
            'documentable_id' => $id,
            'documentable_type' => $this->docType
        ]);
    }
}