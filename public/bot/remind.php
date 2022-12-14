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

  public $emoji, $keyboard, $string;


  public function __construct($emoji, $string)
  {

    $this->emoji = $emoji;

    $this->string = $string;

    $this->keyboard = array();

    $this->db = new Database(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

    $this->telegram = new Telegram(BOT_TOKEN);

  }



  public function run()
  {
//    while (true) {

      $this_day = date('d');
      $this_time = date('H:i');

      $tasks = $this->db->getTasks($this_time);

      echo json_encode($tasks);

      foreach ($tasks as $task) {

          if (date("H:i", strtotime($task['remind_time'])) == $this_time) {

              echo $task['remind_time'] . "\n";

              $chat_id = $task['chat_id'];

              $text = $task['name'];

              $task_id = $task['task_id'];

              $reply_markup = $this->telegram->buildInlineKeyBoard(
                  [[
                      ['text' => $this->string['menu_yes'], 'callback_data' => "$task_id:1"],
                      ['text' => $this->string['menu_no'], 'callback_data' => "$task_id:-1"]
                  ]]
              );

              $content = array(
                  'chat_id' => $chat_id,
                  'text' => $text,
                  'reply_markup' => $reply_markup,
                  'parse_mode' => 'HTML',
              );

              if (($this_day != $task['day_off1'] && $this_day != $task['day_off2']) && $task['rule'] != 'ADMIN') {
                  $this->telegram->sendMessage($content);
              }

              sleep(61);
          } // if

      } // foreach



//    } // while

  } // run

}




?>
