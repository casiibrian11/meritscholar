<?php

namespace App\Libraries;

use App\Models\User;
use App\Libraries\Brevo;

class Notifications
{
    public static function notify($userId, $subject = "Isabela State University", $message = "")
    {
        $user = User::where('id', $userId)->first();

        if (empty($user)) {
            return null;
        }

        $brevo = new Brevo();

        $name = "{$user['first_name']} {$user['last_name']}";
        $from = config('mail')['from']['name'];

        $email = $user['email'];

        $mail = [
            'htmlContent' => $message,
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'senderEmail' => config('mail')['from']['address'],
            'senderName' => config('mail')['from']['name']
        ];

        $result = $brevo->executeEmailsBatchSend($mail);
        return $result;
    }
}
