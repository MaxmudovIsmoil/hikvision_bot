<?php

namespace App\Http\Controllers;


use App\Models\TaskDone;
use App\Models\User;
use App\Helpers\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use function Psy\debug;

class WebHookController
{

    public $message;

    protected $telegram;

    public function __construct(Telegram $telegram)
    {
        $this->telegram = $telegram;
    }


    public function index(Request $request)
    {
        $data = $request->all();
        Log::debug($data);

        $message    = isset($data['message']) ? $data['message'] : [];
        $chat_id    = isset($message['chat']) ? $message['chat']['id'] : null;
        $first_name = isset($message['from']) ? $message['from']['first_name'] : '';
        $text       = isset($message['text']) ? $message['text'] : '';

        if ($text == '/start' && !$this->check_chatID($chat_id)) {
            $this->telegram->sendSharePhoneBtn($chat_id, $first_name);
        }


        // share phone number
        if (isset($message['contact']))
            $this->user_share_phone_save_chat_id($message, $chat_id, $first_name);
        else if (strlen($text) == 13)
            $this->text_phone_save_chat_id($message, $chat_id, $first_name);


        // $this->telegram->sendMessage($chat_id, "<code>salom</code>");

        // task save -> 1 - done or -1 - fail
        $this->task_done_save($data);

    }



    public function task_done_save($data)
    {
        if (isset($data['callback_query'])) {

            $callback_id    = $this->telegram->Callback_ID($data);
            $chat_id        = $this->telegram->Callback_ChatID($data);
            $old_text       = $this->telegram->Callback_Message($data)['text'];
            $message_id     = $this->telegram->Callback_Message_ID($data);
            $callback_data  = $this->telegram->Callback_Data($data);

            try {
                $user_id        = $this->check_chatID($chat_id);
                $task_id        = explode(':', $callback_data)[0];
                $task_status    = explode(':', $callback_data)[1];

                $confirmation_time = date("Y-m-d H:i:s", $this->telegram->Callback_Message_Date($data));
                $time = $this->get_format_time($confirmation_time);


                TaskDone::create([
                    'user_id'           => $user_id,
                    'task_id'           => $task_id,
                    'status'            => $task_status,
                    'confirmation_time' => $time,
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s'),
                ]);

                // inline btn click, clock icon hide
                $this->telegram->answerCallbackQuery($data);

                if ($task_status == 1)
                    $content  = array(
                        'chat_id' 		=> 	$chat_id,
                        'message_id'	=>	$message_id,
                        'inline_message_id'	=>	$message_id,
                        'text'				=>	$old_text." ".$this->telegram->emoji('done'),
                    );
                else
                    $content  = array(
                        'chat_id' 		=> 	$chat_id,
                        'message_id'	=>	$message_id,
                        'inline_message_id'	=>	$message_id,
                        'text'				=>	$old_text." ".$this->telegram->emoji('no'),
                    );

                $this->telegram->editMessage($content);
            }
            catch (\Exception $exception) {
                $error_text = $exception->getMessage();
                $this->telegram->sendMessage(env('REPORT_TELEGRAM_ID'), "<code>".json_encode($error_text)."</code>");
            }

        }
    }



    private function text_phone_save_chat_id($message, $chat_id, $first_name)
    {
        $chatID = $this->check_chatID($chat_id);
        if (!$chatID) {
            $entities = isset($message['entities'][0]['type']) ? $message['entities'][0]['type'] : false;

            if ($entities) {
                $phone = $message['text'];

                $check = $this->check_user_phone($phone, $chat_id);

                if ($check)
                    $this->telegram->removeSharePhoneBtn($chat_id, $first_name);
                else
                    $this->telegram->sendMessage($chat_id, 'Kechirasiz. Bu bot faqat <b>Hikvision</b> hodimlari uchun.');
            }
        }
    }



    private function user_share_phone_save_chat_id($message, $chat_id, $first_name)
    {
        try {
            $chatID = $this->check_chatID($chat_id);
            if (!$chatID) {
                $phone = $message['contact']['phone_number'];

                $check = $this->check_user_phone($phone, $chat_id);

                if ($check)
                    $this->telegram->removeSharePhoneBtn($chat_id, $first_name);
                else
                    $this->telegram->sendMessage($chat_id, 'Kechirasiz. Bu bot faqat <b>Hikvision</b> hodimlari uchun.');
            }
            else {
                $text = $first_name . " siz <b>Hikvision</b> hodimisiz,\nsizga ish vazifalaringiz eslatib turiladi.";
                $this->telegram->sendMessage($chat_id, $text);
            }
        }
        catch(\Exception $exception) {
            $error_text = $exception->getMessage();
            $this->telegram->sendMessage(env('REPORT_TELEGRAM_ID'), "<code>".json_encode($error_text)."</code>");
        }
    }



    private function check_user_phone($phone, $chat_id)
    {
        try {
            if (strlen($phone) < 9)
                return false;

            $user = User::where('phone', 'like', '%'.$phone)->first();
            if ($user) {
                User::where('id', $user->id)->update(['chat_id' => $chat_id]);
                return true;
            }
            else
                return false;
        }
        catch(\Exception $exception) {
            $error_text = $exception->getMessage();
            $this->telegram->sendMessage(env('REPORT_TELEGRAM_ID'), "<code>".json_encode($error_text)."</code>");
        }
    }



    private function check_chatID($chat_id) {
        $user = User::where('chat_id', $chat_id)->first();
        if (!$user)
            return false;

        return $user->id;
    }



    public function get_format_time($confirmation_time)
    {
        $current_date   = date("Y-m-d H:i:s");
        $seconds = (strtotime($current_date) - strtotime($confirmation_time)); // seconds;

        $secs = $seconds % 60;
        $hrs = $seconds / 60;
        $mins = $hrs % 60;
        $hrs = $hrs / 60;

        return ((int)$hrs . ":" . (int)$mins . ":" . (int)$secs);
    }

}
