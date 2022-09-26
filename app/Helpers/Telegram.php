<?php

namespace App\Helpers;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Telegram
{

    protected $http;

    protected $bot_token;

    const url = 'https://api.tlgr.org/bot';

    public function __construct(Http $http, $bot_token)
    {
        $this->http = $http;

        $this->bot_token = $bot_token;
    }

    public function emoji($emoji)
    {
        $array = array(
            'phone' => json_decode('"\ud83d\udcf1"'),
            'ok'    => json_decode('"\uD83C\uDD97"'),
            'icon_error' => json_decode('"\u26a0"'),
            'back'  => json_decode('"\u2b05"'),
            'done'  => json_decode('"\u2705"'),
            'no'    => json_decode('"\u26d4\ufe0f"'),
        );
        return $array[$emoji];
    }

    public function send($method, $data)
    {
        return $this->http::post(self::url.$this->bot_token."/". $method, $data);
    }


    public function sendMessage($chat_id, $text)
    {
        return $this->send('sendMessage', [
            'chat_id'    => $chat_id,
            'text'       => $text,
            'parse_mode' => 'HTML'
        ]);
    }

    public function editMessage($content)
    {
        return $this->send('editMessageText', $content);
    }


    public function answerCallbackQuery($data)
    {
        $url = self::url.$this->bot_token."/answerCallbackQuery";

        $callbackData = [
            'callback_query_id'	=> $this->Callback_ID($data),
            'show_alert'	=> false,
            'text'          => ''
        ];

        return $this->sendAPIRequest($url, $callbackData);
    }

    private function sendAPIRequest($url, array $content, $post = true) {

        if (isset($content['chat_id'])) {
            $url = $url . "?chat_id=" . $content['chat_id'];
            unset($content['chat_id']);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        if($result === false) {
            $result = json_encode(array('ok'=>false, 'curl_error_code' => curl_errno($ch), 'curl_error' => curl_error($ch)));
        }
        curl_close($ch);

        return $result;
    }


    public function sendDocument($chat_id, $file, $reply_id = null)
    {
        return $this->http::attach('document', Storage::get('/public/'.$file), 'document.jpg')
            ->post(self::url . $this->bot_token . '/sendDocument', [
                'chat_id' => $chat_id,
            ]);
    }


    public function sendButtons($chat_id, $text, $button)
    {
        return $this->send('sendMessage', [
            'chat_id'      => $chat_id,
            'text'         => $text,
            'parse_mode'   => 'HTML',
            'reply_markup' => $button,
        ]);
    }

    public function editButton($chat_id, $text, $button, $message_id)
    {
        return $this->send('sendMessageText', [
            'chat_id'      => $chat_id,
            'text'         => $text,
            'reply_markup' => $button,
            'parse_mode'   => 'HTML',
            'message_id'   => $message_id
        ]);
    }


    public function sendSharePhoneBtn($chat_id, $first_name)
    {
        $text = "Assalomu alaykum <b>$first_name</b> botga hush kelibsiz.\nIltimos telefon raqamingizni kiriting!\nMisol uchun: +998990885544";

        $reply_markup = array(
            'keyboard' => [[[ 'text' => $this->emoji('phone').' Telefon raqamni jo\'natish', 'request_contact' => true ]]],
            'one_time_keyboard' => false,
            'resize_keyboard' => true,
            'selective' => false
        );

        $this->send('sendMessage', [
            'chat_id'   => $chat_id,
            'text'      => $text,
            'parse_mode'=> 'HTML',
            'reply_markup' => json_encode($reply_markup, true),
        ]);
    }


    public function removeSharePhoneBtn($chat_id, $first_name)
    {
        $text = $first_name . " siz <b>Hikvision</b> hodimisiz,\nsizga ish vazifalaringiz eslatib turiladi.";
        $reply_markup = json_encode(['remove_keyboard' => true]);

        return $this->send('sendMessage', [
            'chat_id'      => $chat_id,
            'text'         =>  $text,
            'parse_mode'   => 'HTML',
            'reply_markup' => $reply_markup,
        ]);
    }




    public function Callback_ID($data) {
        if (isset($data["callback_query"]["id"]))
            return $data["callback_query"]["id"];
        return 0;
    }

    public function Callback_Data($data) {
        return $data["callback_query"]["data"];
    }

    public function Callback_Message($data) {
        return $data["callback_query"]["message"];
    }

    public function Callback_Message_ID($data) {
        return $data["callback_query"]["message"]['message_id'];
    }

    public function Callback_Message_Date($data) {
        return $data['callback_query']['message']['date'];
    }

    public function Callback_ChatID($data) {
        return $data["callback_query"]["message"]["chat"]["id"];
    }

}
