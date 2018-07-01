<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 26/06/18
 * Time: 11:47 AM
 */

namespace App\Service;

use App\Mail\InvitationMail;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
//use Nexmo\Laravel\Facade\Nexmo;

class AppInvitesService extends Service
{

    protected function removeKey($key,$item){
        $k = array_search($key,array_keys($item));
        if(!$k == false)
            array_splice($item,$k,1);
        return $item;
    }

    protected function hasTag($item){
        return array_key_exists('_tag',$item);
    }

    protected function cherryPick($items,&$invited){
        foreach ($items as $item) {
            if (!$this->hasTag($item)) {
                $this->errors['no_tag'][] = $item['id'];
                continue;
            }

            if ($item['_tag'] == 'INVITED') {
                $invited[] = $item;
                $this->removeKey("_tag",$item);
            }elseif($item['_tag'] == 'NOT_INVITED' || $item['_tag'] == 'NOT_FOLLOWING'){
                continue;
            }else{
                $this->errors['tag_unrecognized'][] = $item['id'];
            }
        }
    }

    public function searchForFriends(Request $request){
        //Start a curl session
        $client = new \GuzzleHttp\Client();
        //Bulk search
        $body = "";
        foreach ($request->users as $user){
            if ($user['tel'] != "" || $user['tel'] != null){ //SEARCH TELEPHONE NUMBER
                //For users with multiple numbers explode and search all numbers
                foreach (explode(";",$user['tel']) as $tel){
                    $body = $body . "{\"index\" : \"timespace\",\"type\":\"user\"}
                         {\"query\" : {
                               \"match_phrase\" : {
                                    \"tel\": \"{$tel}\"
                                 }
                             }
                         }";
                }
            }

            if ($user['email'] != "" || $user['email'] != null){ //SEARCH EMAIL NUMBER
                //For users with multiple email search all emails
                foreach (explode(";",$user['email']) as $email){
                    $body = $body . "{\"index\" : \"timespace\",\"type\":\"user\"}
                         {\"query\" : {
                               \"match_phrase\" : {
                                    \"email\": \"{$email}\"
                                 }
                             }
                         }";
                }
            }
        }

        $result = $client->post(config('plastic.connection.hosts')[0] . "/timespace/users/_msearch",[
            'body' => $body
        ]);
        $this->parseSearchResults($request,json_decode((string) $result->getBody()));
        $this->status = $result->getStatusCode();
        return empty($this->errors);
    }

    private function parseSearchResults(Request $request, $result){
        //Users found
        $foundUsers = array();
        foreach ($result->responses as $response){ //For each response
            foreach ($response->hits->hits as $hit){
                $foundUsers[] = $hit->_source;
            }
        }
        $this->data = $foundUsers;
    }

    public function commitAppInvites(Request $request,Mailer $mailer){
        $followings = array(); $invites = array();

        $this->cherryPick($request->users,$invites);

        $this->addInvites($request,$mailer,$invites);

        return empty($this->errors);
    }

    private function addInvites(Request $request,Mailer $mailer,$invites){
        try {
            foreach ($invites as $invite){
                $inviteCode = $this->random_num(6);

                //GENERATE NEW INVITATION
                $this->data['invitation'][] = Invitation::create([
                    'inviteCode' => $inviteCode,
                    'inviter_id' => /*$request->user()->id*/1,
                    'invitee_name' => $invite['first_name'],
                    'invitee_email' => $invite['email'],
                    'invitee_tel' => $invite['tel'],
                ]);

                if ($request->inviteBy == 'email'){
                    if ($invite['email'] != null || $invite['email'] != "")
                        $this->sendEmail($mailer,$invite['email'],$inviteCode);
                    elseif ($invite['tel'] != null || $invite['tel'] != "")
                        $this->sendSMS($invite['tel'],$inviteCode);
                    else
                        $this->errors['invites_not_sent'] = $invite;
                } elseif($request->inviteBy == 'sms') {
                    if ($invite['tel'] != null || $invite['tel'] != "")
                        $this->sendSMS($invite['tel'],$inviteCode);
                    elseif ($invite['email'] != null || $invite['email'] != "")
                        $this->sendEmail($mailer,$invite['email'],$inviteCode);
                    else
                        $this->errors['invites_not_sent'] = $invite;
                }
            }
        }catch (\Exception $e){
            $this->message = "re_try";
            $this->errors["add_invite"] = $e->getMessage();
            $this->status = 400;
        }

    }

    private function sendEmail(Mailer $mailer,$email,$inviteCode){
        $mailer->to(explode(";",$email)[0])->send(new InvitationMail($inviteCode));
    }

    private function sendSMS($tel,$inviteCode){
        //Make sms on jobs instead of the main process
//        Nexmo::message()->send([
//            'to'   => explode(";",$tel)[0],
//            'from' => 'NEXMO',
//            'text' => "I found this great app for managing my time,
//                        my team and making schedules very easily.
//                        You should really try it, download it at www.timespace.com/dl/{$inviteCode}"
//        ]);
    }

    private function random_num($length) {
        $randStr = "";
        srand((double) microtime(TRUE) * 1000000);
        //our array add all letters and numbers if you wish
        $chars = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p',
            'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5',
            '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
            'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        for ($rand = 0; $rand <= $length; $rand++) {
            $random = rand(0, count($chars) - 1);
            $randStr .= $chars[$random];
        }
        return $randStr;
    }
}