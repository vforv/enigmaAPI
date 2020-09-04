<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    public function confirmRegistration()
    {
        $data = array('name' => "Virat Gandhi", "link" => "https://www.google.com");
        Mail::send('confirmRegistration', $data, function ($message) {
            $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel HTML Testing Mail');
            $message->from('xyz@gmail.com', 'Virat Gandhi');
        });
        echo "HTML Email Sent. Check your inbox.";
    }

    public function orderMail()
    {
        $data = array('name' => "Virat Gandhi");
        Mail::send('mail', $data, function ($message) {
            $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel Testing Mail with Attachment');
            $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
            $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
            $message->from('xyz@gmail.com', 'Virat Gandhi');
        });
        echo "Email Sent with attachment. Check your inbox.";
    }
}
