<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 4/07/18
 * Time: 10:57 AM
 */

namespace App\Service;


class ReviewService extends CRUDService
{

    public function getModelType()
    {
        return 'App\Models\Review';
    }

    public function getEventChannel()
    {
        return 'review';
    }
}