<?php

namespace App\Helpers;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Telegram  {

    protected $http;
    protected $bot_token;
    const url = 'https://api.tlgr.org/bot';

    public function __construct(Http $http, $bot_token)
    {
        $this->http = $http;
        $this->bot_token = $bot_token;
    }


    public function sendMessage($chat_id, $message)
    {
        return $this->http::post(self::url.$this->bot_token.'/sendMessage', [
            'chat_id'    => $chat_id,
            'text'       => $message,
            'parse_mode' => 'html'
        ]);
    }

    public function editMessage($chat_id, $message, $message_id)
    {
        return $this->http::post(self::url.$this->bot_token.'/sendMessageText', [
            'chat_id'    => $chat_id,
            'text'       => $message,
            'parse_mode' => 'html',
            'message_id' => $message_id
        ]);
    }

    public function sendDocument($chat_id, $file, $reply_id = null)
    {
        return $this->http::attach('document', Storage::get('/public/'.$file), 'document.jpg')
            ->post(self::url . $this->bot_token . '/sendDocument', [
                'chat_id' => $chat_id,
            ]);
    }


    public function sendButtons($chat_id, $message, $button)
    {
        return $this->http::post(self::url . $this->bot_token . '/sendMessage', [
            'chat_id'      => $chat_id,
            'text'         => $message,
            'parse_mode'   => 'html',
            'reply_markup' => $button,
        ]);
    }

    public function editButton($chat_id, $message, $button, $message_id)
    {
        return $this->http::post(self::url . $this->bot_token . '/sendMessageText', [
            'chat_id'      => $chat_id,
            'text'         => $message,
            'reply_markup' => $button,
            'parse_mode'   => 'html',
            'message_id'   => $message_id
        ]);
    }




}
