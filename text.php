<?php

include_once 'config.php';

// include 'libs/Telegram.php';

include 'libs/Database.php';

//$db = new Database(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

ob_start();

function bot($method, $data = []) {

    $url = 'https:://api.telegram.org/bot'.BOT_TOKEN."/$method";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $res = curl_error($ch);

    if (curl_error($ch))
        var_dump(curl_error($ch));
    else
        return json_decode($res);
}

$update = json_decode(file_get_contents('php://input'));

$message = $update->message;
$chat_id = $message->chat->id;
$message_id = $message->message->id;
$text = $message->text;




if ($text == '/start') {
    bot('sendMessage', [
        'chat_id'   => $chat_id,
        'message_id'=> $message_id,
        'parse_mode'=> 'markdown',
        'text'      => 'Assalomu alaykum'
        ]);
}

