<?php
/**
 * Created by PhpStorm.
 * User: KIRABUI-PC
 * Date: 6/12/2018
 * Time: 1:32 AM
 */

namespace App\Service;


class Service
{
    /**
     * @var $message string - Contains a message to be sent back along with the
     * response to the client.
     */
    public $message = "success";

    /**
     * @var $status integer - Contains the response status to be sent back.
     */
    public $status = 200;

    /**
     * @var $data array - Contains the data to be returned
     * after processing the request.
     */
    public $data = [];

    /**
     * @var $errors array - Contains all errors encountered during processing the request.
     */
    public $errors = [];

}