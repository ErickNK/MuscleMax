<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 4/07/18
 * Time: 11:13 AM
 */

namespace App\Service;


class TagService extends CRUDService
{

    public function getModelType()
    {
        return 'App\Models\Tag';
    }

    public function getEventChannel()
    {
        return 'tag';
    }

}