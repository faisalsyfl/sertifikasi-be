<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;

class MailController extends Controller
{
    public function sendMail(Request $request)
    {
        $data = array('name' => "Kowlon");
        Mail::send('mail', $data, function ($message) {
            $message->to('wahyuanggana1@gmail.com', 'Email PDF')->subject('Laravel HTML Testing Mail');
            $message->from('sertifikasi@b4t.go.id', 'Kowlon');
            $message->attach('/Applications/XAMPP/xamppfiles/htdocs/sertifikasi-be/public/1.pdf');
        });
        echo "HTML Email Sent. Check your inbox.";
    }
}