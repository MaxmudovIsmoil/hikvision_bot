<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'remind_time',
        'day_off1',
        'day_off2',
        'month',
        'year',
        'created_at',
        'updated_at',
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function task()
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }

}
