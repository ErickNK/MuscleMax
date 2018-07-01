<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 15/06/18
 * Time: 10:20 PM
 */

namespace App\Service;


use App\Http\Controllers\AuthController;
use App\Models\User;
use App\Traits\DoesResponses;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService extends Service
{

    public function signInWithGoogle(Request $request){
        $client = new \GuzzleHttp\Client();
        try {
            //MAKE REQUEST TO GOOGLE FOR VALIDATION
            $res = $client->get('https://www.googleapis.com/oauth2/v3/tokeninfo',[
                'query' => ['id_token' => $request->token]
            ]);
            $body = json_decode((string) $res->getBody());

            if ($body->email_verified){ //IF THE USER IS GOOGLE AUTHENTICATED

                $user = User::where('email', '=', $body->email)->get()->first(); //Check if user exists in the database

                if ($user == null) { //REGISTER USER

                    $user = [
                        'first_name' => $body->given_name ?: "",
                        'second_name' => "",
                        'surname' => $body->family_name ?: "",
                        'email' => $body->email ?: "",
                        'pictures' => [
                            0 => [
                                'remote_location' => $body->picture ?: "",
                            ]
                        ]
                    ];
                    $this->data = ['user' => $user];
                    $this->message = "registration_required";
                    $this->status = 200;
                    return empty($this->errors);
                } else { //AUTHENTICATE USER
                    $request->merge([
                        "fromUser" => true,
                        "user" => $user,
                        "email" => $user->email,
                        "password" => $user->password
                    ]);
                    return $request;
                }
            }else{
                $this->message = "re_authenticate";
                $this->errors["signInWithGoogle"] = "Google authentication Failed";
                $this->status = 400;
                return empty($this->errors);
            }

        } catch (\Exception $e) {
            $this->message = "re_try";
            $this->errors["signInWithGoogle"] = $e->getMessage();
            $this->status = 400;
            return empty($this->errors);
        }
    }

    public function signInWithFacebook(Request $request,\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb){
        try {
            $res = $fb->get('/me?fields=first_name,middle_name,last_name,email,picture', $request->token);
            $userNode = $res->getGraphUser();

            $user = User::where('email', '=', $userNode->getEmail())->get()->first(); //Check if user exists in the database

            if ($user == null) { //REGISTER USER

                $user = [
                    'first_name' =>  $userNode->getFirstName() ?: "",
                    'second_name' =>  $userNode->getMiddleName() ?: "",
                    'surname' =>  $userNode->getLastName() ?: "",
                    'email' =>  $userNode->getEmail() ?: "",
                    'pictures' => [
                        0 => [
                            'remote_location' =>  $userNode->getPicture()->getUrl() ?: "",
                        ]
                    ]
                ];

                $this->data = ['user' => $user];
                $this->message = "registration_required";
                $this->status = 200;
                return empty($this->errors);
            } else { //AUTHENTICATE USER
                $request->merge([
                    "fromUser" => true,
                    "user" => $user,
                    "email" => $user->email,
                    "password" => $user->password
                ]);
                return $request;
            }

        } catch (\Exception $e) {
            $this->message = "re_try";
            $this->errors["signInWithFacebook"] = $e->getMessage();
            $this->status = 400;
            return empty($this->errors);
        }
    }
}