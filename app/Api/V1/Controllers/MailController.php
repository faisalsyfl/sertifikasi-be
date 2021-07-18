<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;

class MailController extends Controller
{
    public function sendMail(Request $request)
    {
        self::setupMail($request->all());
        echo "HTML Email Sent. Check your inbox.";
    }

    static function setupMail($data){
        $name = isset($data["name"]) ? $data["name"] : "PT. ABC";
        $email = isset($data["email"]) ? $data["email"] : "wahyuanggana1@gmail.com";
        $subject = isset($data["subject"]) ? $data["subject"] : "Payment Document";
        $attachment = isset($data["attachment"]) ? $data["attachment"] : null;

        $data = array('name' => $name);
        Mail::send('mail', $data, function ($message) use ($email, $attachment, $subject) {
            $message->to($email, 'Email PDF')->subject($subject);
            $message->from('sertifikasi@b4t.go.id', 'Sertifikasi B4T');

            if($attachment){
                $message->attach($attachment);
            }
        });
    }
}
