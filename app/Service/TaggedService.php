<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 4/07/18
 * Time: 11:27 AM
 */

namespace App\Service;


class TaggedService extends CRUDService
{

    public function getModelType()
    {
        return 'App\Models\Tagged';
    }

    public function getEventChannel()
    {
        return 'tagged';
    }

}