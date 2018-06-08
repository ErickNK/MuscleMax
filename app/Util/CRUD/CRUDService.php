<?php
/**
 * Created by PhpStorm.
 * User: erikn
 * Date: 9/22/2017
 * Time: 8:14 PM
 */

namespace App\Util\CRUD;


interface CRUDService
{
    public function getModelType();
    public function getEventChannel();
}