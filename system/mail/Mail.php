<?php

namespace System\mail;

class Mail
{
    public static function send($from, $to, $subject, $txt): void
    {
        $headers = "From: " . $from . "\r\n";
        mail($to, $subject, $txt, $headers);
    }
}