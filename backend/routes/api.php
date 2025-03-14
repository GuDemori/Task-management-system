<?php

use App\Http\Controllers\SubtaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Models\Subtask;

Route::apiResource('tasks', TaskController::class);
Route::apiResource('subtasks', SubtaskController::class);
