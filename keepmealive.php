<?php

include './constants.php';

echo 'Hey There!';


function sendMessage($chat_id, $text)
{
    global $bot_api;
    $request_url="https://api.telegram.org/bot".$bot_api."/";
    $url = $request_url . 'sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($text).'&parse_mode=Markdown';
    file_get_contents($url);
}

function sendPhoto($chat_id, $photo, $caption='')
{
    global $bot_api;
    $request_url="https://api.telegram.org/bot".$bot_api."/";
    $url=$request_url.'sendPhoto?chat_id='.$chat_id.'&photo='.urlencode($photo).'&caption='.urlencode($caption).'&parse_mode=Markdown';
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


function sendUnsplash()
{
    global $chatids;
    $unsplash=getUnspalsh();
    $caption=ucfirst($unsplash['alt']).'.

A photo by ['.$unsplash['name'].']('.$unsplash['link'].').';
    foreach ($chatids as $chatid) {
        sendPhoto($chatid, $unsplash['photo'],$caption);
    }
}

function getWordnik()
{
    global $wordnik_api;
    $request_url='https://api.wordnik.com/v4/words.json/wordOfTheDay?api_key='.$wordnik_api;
    $result=json_decode(file_get_contents($request_url), true);
    $wordnik=array();
    $wordnik['word']=$result['word'];
    $wordnik['def']=$result['definitions'][0]['text'];
    $wordnik['examples'][0]=$result['examples'][0]['text'];
    $wordnik['examples'][1]=$result['examples'][1]['text'];
    return $wordnik;
}

function sendWordnik()
{
    global $chatids;
    $wordnik=getWordnik();
    $text='*'.$wordnik['word'].'*

▶️ '.$wordnik['def'].'

Examples:
🔻 '.$wordnik['examples'][0].'

🔻 '.$wordnik['examples'][1];
    foreach ($chatids as $chatid) {
        sendMessage($chatid,$text);
    }
}

if(rand()%2==0){
    sendWordnik();
}else{
    sendUnsplash();
}

