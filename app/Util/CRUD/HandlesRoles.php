<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 6/1/18
 * Time: 11:42 AM
 */

namespace App\Util\CRUD;


use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait HandlesRoles
{
    /**
     * Assign a normal role to the newly created user.
     * @param $request Request
     * @param $model \App\Models\User
     */
    protected function assignRole($request,&$model){
        $model->attachRole(Role::where('name','USER')->first());
    }

    /**
     * Assign a normal role to the newly created user.
     * @param $request Request
     * @param $model \App\Models\User
     */
    protected function addRole($request,&$model){
        $model->roles()->attach(Role::where('name','USER')->first());
    }
}