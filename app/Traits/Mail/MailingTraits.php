<?php

namespace App\Traits\Mail;

use Mail;

trait MailingTraits
{
    public static function confirmRegistration($data)
    {
        Mail::send('confirmRegistration', $data, function ($message) use ($data) {
            $message->to($data['email'], $data['name'])->subject
            ('Registracija');
            $message->from('no-reply@vebcentar.me', 'Montefish');
        });
        return true;
    }

    public static function orderPDF($data)
    {
        $emails = ["montefish@t-com.me", $data["email"]];
        foreach ($emails as $email) {
            Mail::send('orderMail', ["name" => $data["name"]], function ($message) use ($data, $email) {
                $message->to($email, "Porudžbina")->subject
                ("Porudžbina #" . $data["id"]);
                $message->from('no-reply@vebcentar.me', 'Montefish');
                $message->attach($data["pdf"]);
            });
        }
        return true;
    }
}
