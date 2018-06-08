<?php

namespace App\Service;

use App\Util\CRUD\CRUDService;
use App\Util\CRUD\HandlesCRUD;

/**
 * Created by PhpStorm.
 * User: erick
 * Date: 5/24/18
 * Time: 12:52 AM
 */

class UserService implements CRUDService
{
    use HandlesCRUD;

    public function getModelType()
    {
        return 'App\Models\User';
    }

    public function getEventChannel()
    {
        return 'user';
    }

}