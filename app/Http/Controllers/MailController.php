<?php

namespace App\Http\Controllers;

use App\Mail\InvitationMail;
use App\Traits\DoesResponses;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;

class MailController extends Controller
{

    public function sendInvitationLink(Request $request,Mailer $mailer){
        $this->validate($request,[
            "emails" => "required|array"
        ]);

        $mailer->to($request->emails)->send(new InvitationMail("Invitation"));

        return $this->successResponse([
            "invite" => "Sent successfully"
        ]);
    }
}
