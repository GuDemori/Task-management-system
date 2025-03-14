<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = ['title', 'description', 'due_date', 'status'];

    public function subtasks() {
        return $this->hasMany(Subtask::class);
    }

    protected $with = ['subtasks'];

}
