<?php 

include_once 'config.php';

include 'libs/Telegram.php';

include 'libs/Database.php';

$remind = new Remind($emoji, $string);

$remind->run();



class Remind
{
  private $db;

  private $telegram;

  private $emoji, $keyboard, $string;


  public function __construct($emoji, $string)
  { 

    $this->emoji = $emoji;

    $this->string = $string;

    $this->db = new Database(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

    $this->initMenu();

    $this->telegram = new Telegram(BOT_TOKEN);

  }

  function initMenu()
  {

    $this->keyboard = array();

    // $this->keyboard['inKeyboard'] = 
    // [
    //   [
    //     [
    //       'text'          => $this->string['menu_yes'],
    //       'callback_data' => "121:1"
    //     ],
    //     [
    //       'text'          => $this->string['menu_no'],
    //       'callback_data' => "121:0"
    //     ]
    //   ]  
    // ];

  }

  public function run()
  {

    while (true) {

      $this_day = date('Y-m-d');
      $this_time = date('H:i');

      $tasks = $this->db->getTasks($this_day, $this_time);
      
      echo json_encode($tasks);

      foreach ($tasks as $task) {

      	echo $task['remind_time'] . "\n";

        $chat_id = $task['chat_id'];


        $text = $task['name'];
        $task_id = $task['task_id'];

        // $reply_markup = $this->telegram->buildInlineKeyBoard($this->keyboard['inKeyboard']);
        $reply_markup = $this->telegram->buildInlineKeyBoard(
          [[
            ['text' => $this->string['menu_yes'], 'callback_data' => "$task_id:1"],
            ['text' => $this->string['menu_no'], 'callback_data' => "$task_id:0"]
          ]]
        );

        $content = array
        (
          'chat_id'     => $chat_id,
          'text'        => $text,
          'reply_markup'=> $reply_markup,
          'parse_mode'  => 'HTML',
        );

        $result = $this->telegram->sendMessage($content);
	
      } // foreach

      sleep(61);

    } // while

  } // run

}




?>