<?php

namespace App\Listeners;


use App\Events\TaskDoneEvent;
use App\Helpers\Telegram;

class TelegramSubscriber
{

    protected $telegram;


    public function __construct(Telegram $telegram)
    {
        $this->telegram = $telegram;
    }



    public function taskDoneStore($event)
    {

    }


    public function subscribe($event)
    {
        $event->listen(
            TaskDoneEvent::class, [
                TelegramSubscriber::class, 'taskDoneStore'
            ]
        );
    }

}
