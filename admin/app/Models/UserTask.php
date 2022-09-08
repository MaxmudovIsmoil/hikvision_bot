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
        'start_day',
        'end_day',
        'created_at',
        'updated_at',
    ];


//    public function city()
//    {
//        return $this->hasOne(TaskDone::class, 'id', 'city_id');
//    }
}
