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
        sendPhoto($chatid, $unsplash['photo'],$caption.'

'.$chatid);
    }
}

function getWordnik()
{
    global $wordnik_api;
    $request_url='https://api.wordnik.com/v4/words.json/randomWord?hasDictionaryDef=true&includePartOfSpeech=noun&excludePartOfSpeech=verb%2C%20adjective%2C%20adverb%2C%20interjection%2C%20pronoun%2C%20preposition%2C%20abbreviation%2C%20affix%2C%20article%2C%20auxiliary-verb%2C%20conjunction%2C%20definite-article%2C%20family-name%2C%20given-name%2C%20idiom%2C%20imperative%2C%20noun-plural%2C%20noun-posessive%2C%20past-participle%2C%20phrasal-prefix%2C%20proper-noun%2C%20proper-noun-plural%2C%20proper-noun-posessive%2C%20suffix%2C%20verb-intransitive&minCorpusCount=10000&maxCorpusCount=-1&minDictionaryCount=2&maxDictionaryCount=-1&minLength=5&maxLength=-1&api_key='.$wordnik_api;
    $result=json_decode(file_get_contents($request_url), true);
    $wordnik=array();
    $wordnik['word']=$result['word'];
    $request_url='https://api.wordnik.com/v4/word.json/'.$wordnik['word'].'/definitions?limit=5&includeRelated=false&useCanonical=false&includeTags=false&api_key='.$wordnik_api;
    $result=json_decode(file_get_contents($request_url), true);
    $wordnik['url']=$result[0]['wordnikUrl'];
    $i=0;
    foreach ($result as $def) {
        $wordnik['defs'][$i]=$def['text'];
        $i=$i+1;
    }
    $request_url='https://api.wordnik.com/v4/word.json/'.$wordnik['word'].'/examples?includeDuplicates=false&useCanonical=false&limit=5&api_key='.$wordnik_api;
    $result=json_decode(file_get_contents($request_url), true);
    $i=0;
    foreach ($result['examples'] as $example) {
        $wordnik['examples'][$i]=$example['text'];
        $i=$i+1;
    }
    return $wordnik;
}

function sendWordnik()
{
    global $chatids;
    $wordnik=getWordnik();
    $text='['.$wordnik['word'].']('.$wordnik['url'].')

_Definitions:_
';
    foreach ($wordnik['defs'] as $def) {
        $text=$text.'▶️ '.$def.'

';
    }
    $text=$text.'

_Examples:_
';
    foreach ($wordnik['examples'] as $ex) {
        $text=$text.'🔻 '.$ex.'

';
    }
    foreach ($chatids as $chatid) {
        sendMessage($chatid,$text.'

'.$chatid);
    }
}

if(rand()%2==0){
    sendWordnik();
}else{
    sendUnsplash();
}

