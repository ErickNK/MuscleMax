<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Service\UserService;
use App\Http\Controllers\Controller;
use App\Util\CRUD\HandlesCRUDRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class UserController extends Controller
{
    use HandlesCRUDRequest;

    /**
     * Create a new controller instance.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->CRUDService = $userService;

        $this->addValidationRules = [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users_95319',
            'password' => 'required|string|min:6',
        ];

        $this->updateValidationRules = [

        ];
    }
}
