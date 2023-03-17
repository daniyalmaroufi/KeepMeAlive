<?php

include './constants.php';

function sendMessage($chat_id, $text)
{
    global $bot_api;
    $request_url="https://api.telegram.org/bot".$bot_api."/";
    $url = $request_url . 'sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($text);
    file_get_contents($url);
}

function sendPhoto($chat_id, $photo, $caption='')
{
    global $bot_api;
    $request_url="https://api.telegram.org/bot".$bot_api."/";
    $url=$request_url.'sendPhoto?chat_id='.$chat_id.'&photo='.urlencode($photo).'&caption='.urlencode($caption).'&parse_mode=Markdown';
    echo $url;
    file_get_contents($url);
}

function getUnspalsh()
{
    global $unsplash_api;
    $request_url='https://api.unsplash.com/photos/random?client_id='.$unsplash_api.'&orientation=landscape';
    $result=json_decode(file_get_contents($request_url), true);
    $unsplash=array();
    $unsplash['photo']=$result['urls']['full'];
    $unsplash['link']=$result['links']['html'];
    $unsplash['name']=$result['user']['name'];
    $unsplash['alt']=$result['alt_description'];
    return $unsplash;
}

$unsplash=getUnspalsh();
$caption=ucfirst($unsplash['alt']).'.

A photo by ['.$unsplash['name'].']('.$unsplash['link'].').';

foreach ($chatids as $chatid) {
    sendPhoto($chatid, $unsplash['photo'],$caption);
}

