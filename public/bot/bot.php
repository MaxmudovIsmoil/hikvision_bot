<?php

include_once 'config.php';

include 'libs/Telegram.php';

include 'libs/Database.php';



$bot = new Bot($emoji, $string);

$bot->run();


class Bot
{
    private $db;

    private $telegram;

    private $message;

    private $emoji, $keyboard, $string;


   public function __construct($emoji, $string)
    {

      $this->emoji = $emoji;

      $this->string = $string;

      $this->db = new Database(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

      $this->telegram = new Telegram(BOT_TOKEN);

    }


    function initMenu()
    {

		$this->keyboard = array();

		$this->keyboard['request_contact'] =
		[
			[
                [
                    'text' => $this->string['request_contact'],
                    'request_contact' => true
                ]
            ],
		];

	}


    public function enterPhone()
    {

        $reply_markup = $this->telegram->buildKeyBoard($this->keyboard['request_contact'], false, true, false);

        if (isset($this->message["contact"]))
        {

            $contact = $this->message["contact"];

            $phone = $contact["phone_number"];

            if (substr($phone, 0, 1) != '+')
                $phone = "+" . $phone;

            $this->check_chatId($phone);

        }
        else
        {
            if ($this->telegram->Text() != '/start') {

                $this->user_done();

            }
            else {

                $chat_id = $this->telegram->ChatID();

                $sql = "SELECT chat_id FROM users WHERE chat_id = '". $chat_id . "'";


                if(!$this->db->selectOne($sql)['chat_id']) {

                    $text = "Assalomu alaykum <b>".$this->telegram->firstName()."</b>\nTelefon raqamingizni kiriting:";

                    $content = array
                    (
                      'chat_id'     => $chat_id,
                      'text'        => $text,
                      'reply_markup'=> $reply_markup,
                      'parse_mode'  => 'HTML',
                    );

                    $this->telegram->sendMessage($content);

                } // if

            } // else

        } // else

    } // enterPhone


    public function run()
    {

        $update = $this->telegram->getData();

//        var_dump($update);

        $this->message = ($update) ? $update["message"] : '';

        $this->initMenu();

        $this->enterPhone();

    }


    public function check_chatId($phone)
    {
        $chat_id = $this->telegram->ChatID();

        $sql = "SELECT id, full_name, username, chat_id, phone FROM users where phone = '".$phone."' LIMIT 1";

        $resSelect = $this->db->selectOne($sql);

        if ($resSelect) {

            if ($resSelect['chat_id']) {

                $text = "Sizga ish vazifalarningiz eslatib turiladi";

            }
            else {

                $sqlUpdate = "UPDATE users SET `chat_id` = ".$chat_id." WHERE phone = '".$phone."'";

                $resUpdate = $this->db->query($sqlUpdate);


                if ($resUpdate)
                    $text =  "Siz hikvision hodimisiz sizga ish vazifalarningiz eslatib turiladi";

                else
                    $text =  "Error writing chat id to database:";

            }
        }
        else {

            $text = "Bu bot faqat <b>Hikvision</b> hodimlari uchun.";

        }

        $reply_markup = $this->telegram->buildKeyBoardHide(true);

        $content = array
        (
            'chat_id'     => $chat_id,
            'text'        => $text,
            'reply_markup'=> $reply_markup,
            'parse_mode'  => 'HTML',
        );

        $this->telegram->sendMessage($content);

    } // check_chatId


    public function user_done()
    {

        if ($this->telegram->Callback_Data()) {

            $chat_id = $this->telegram->ChatID();

            $sql = "SELECT id FROM users WHERE chat_id = '". $chat_id . "'";

            $user_id = $this->db->selectOne($sql)['id'];

            $task_text  = $this->telegram->Callback_Message()['text'];


            $resArr = explode(':', $this->telegram->Callback_Data());

            $task_id = $resArr[0];

            $status = $resArr[1];

            $confirmation_time = date("Y-m-d H:i:s", $this->telegram->Callback_Message()['date']);


            $sqlInsert = "INSERT INTO `task_dones`(`id`, `user_id`, `task_id`, `status`, `confirmation_time`, `created_date`) VALUES(NULL, '".$user_id."', '".$task_id."', '".$status."', '".$confirmation_time."', NOW())";

            $resultInsert = $this->db->insert($sqlInsert);



            $message_id = $this->telegram->Callback_Query()['message']['message_id'];

            $reply_markup = $this->telegram->buildKeyBoardHide(true);


            $callbackData = array(
                'callback_query_id'	=> $this->telegram->Callback_ID(),
                'show_alert'	=> false,
            );
            $this->telegram->answerCallbackQuery($callbackData);


            if ($resultInsert) {

                if ($status) {

                    $editMessage  = array
                    (
                        'chat_id' 		=> 	$chat_id,
                        'message_id'	=>	$message_id,
                        'inline_message_id'	=>	$message_id,
                        'text'				=>	$this->emoji['done'] . " " . $task_text,
                    );

                }
                else {
                    $editMessage  = array
                    (
                        'chat_id' 		=> 	$chat_id,
                        'message_id'	=>	$message_id,
                        'inline_message_id'	=>	$message_id,
                        'text'				=>	$this->emoji['no'] . " " . $task_text,
                    );
                }

                $this->telegram->editMessageText($editMessage);

            } // if

        } // if


    } // user_done


}



?>
