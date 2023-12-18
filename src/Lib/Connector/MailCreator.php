<?php

namespace App\Lib\Connector;

use Google_Service_Gmail_Message;

class MailCreator
{
    public static function create(int $userId, string $to, string $code): Google_Service_Gmail_Message
    {
        $rawMessageString = "From: Vlad Misaila <vladutz.misaila@gmail.com>\r\n";
        $rawMessageString .= "To: <{$to}>\r\n";
        $rawMessageString .= 'Subject: =?utf-8?B?' . base64_encode('Dynamic Forms Generator verification code') . "?=\r\n";
        $rawMessageString .= "MIME-Version: 1.0\r\n";
        $rawMessageString .= "Content-Type: text/html; charset=utf-8\r\n";
        $rawMessageString .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
        $rawMessageString .= "To verify your email, please follow this <a href='http://localhost/api/user/{$userId}/verify/'{$code}>link</a>\r\n";
        $rawMessage = strtr(base64_encode($rawMessageString), ['+' => '-', '/' => '_']);

        $message = new Google_Service_Gmail_Message();
        $message->setRaw($rawMessage);
        return $message;
    }
}