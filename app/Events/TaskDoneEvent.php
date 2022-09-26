<?php


namespace App\Events;

use App\Models\TaskDone;

class TaskDoneEvent
{


    public $task;


    public function __construct(TaskDone $task)
    {
        $this->task = $task;
    }


}
