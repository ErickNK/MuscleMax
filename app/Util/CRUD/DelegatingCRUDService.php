<?php
/**
 * Created by PhpStorm.
 * User: erikn
 * Date: 9/25/2017
 * Time: 9:48 AM
 */

namespace App\Util\CRUD;


use Illuminate\Http\Request;

interface DelegatingCRUDService
{
    /**
     * Persist the items that where in the temporary storage on the
     * client side.
     *
     * @param Request $request
     * @return bool
     */
    public function saveAll(Request $request);

    /**
     * Get all items from the database to sync with the
     * data in the client side.
    */
    public function getAll();

}