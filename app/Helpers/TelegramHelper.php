<?php

namespace App\Helpers;

class TelegramHelper
{
    public static function sendMessage($message)
    {
        $botToken = "8008990284:AAFO2sclysidfgg6b_UsN-UF2RDgfyh80DA";
        $chatId   = "-1002527682969";

        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

        $params = [
            'chat_id'    => $chatId,
            'text'       => $message,
            'parse_mode' => 'HTML'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}
