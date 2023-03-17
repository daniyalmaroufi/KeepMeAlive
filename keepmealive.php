<?php

include './constants.php';

function sendMessage($chat_id, $text)
{
    global $bot_api;
    $request_url="https://api.telegram.org/bot".$bot_api."/";
    $url = $request_url . 'sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($text);
    file_get_contents($url);
}

function sendPhoto($chat_id, $photo)
{
    global $bot_api;
    $request_url="https://api.telegram.org/bot".$bot_api."/";
    $url = $request_url . 'sendPhoto?chat_id=' . $chat_id . '&photo=' . urlencode($photo);
    file_get_contents($url);
}

