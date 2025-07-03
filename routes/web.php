<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RasaController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DMDiaChiController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\FineTuningController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TopicController;

Route::get('auth/login',[LoginController::class, 'login_form']);
Route::get('auth/logout', [LoginController::class, 'logout']);
Route::post('auth/login-submit', [LoginController::class, 'login_submit']);
Route::get('auth/register', [LoginController::class, 'register']);
Route::post('auth/register-submit', [LoginController::class, 'register_submit']);
Route::get('auth/not-permis', [AuthController::class, 'notPermis']);

Route::post('/ask', [OpenAIController::class, 'ask']);
Route::get('ask', [OpenAIController::class, 'get_ask']);

Route::get('/', [ChatController::class, 'index'])->middleware('checkauth');
Route::post('chat/generate', [ChatController::class, 'chat_submit']);//->middleware('checkauth');
Route::get('chat/get-completion', [ChatController::class, 'get_completion']);

Route::get('address/get/{id}', [DMDiaChiController::class, 'getOptions'])->middleware('checkauth');
Route::get('address/get/{id}/{id1}', [DMDiaChiController::class, 'getOptions1'])->middleware('checkauth');

Route::group(['prefix' => 'admin',  'middleware' => 'checkauth'], function(){
    Route::get('/', [AuthController::class, 'admin'])->middleware('role:Admin,Manager');
    Route::get('dashboard', [AuthController::class, 'admin'])->middleware('role:Admin,Manager');

    Route::get('messages', [MessagesController::class, 'list'])->middleware('role:Admin,Manager');
    Route::get('messages/detail/{id}', [MessagesController::class, 'detail'])->middleware('role:Admin,Manager');
    Route::get('messages/delete/{id}', [MessagesController::class, 'delete'])->middleware('role:Admin,Manager');

    Route::get('fine-tuning', [FineTuningController::class, 'list'])->middleware('role:Admin,Manager');
    Route::get('fine-tuning/add', [FineTuningController::class, 'add'])->middleware('role:Admin,Manager');
    Route::post('fine-tuning/create', [FineTuningController::class, 'create'])->middleware('role:Admin,Manager');
    Route::get('fine-tuning/edit/{id}', [FineTuningController::class, 'edit'])->middleware('role:Admin,Manager');
    Route::post('fine-tuning/update', [FineTuningController::class, 'update'])->middleware('role:Admin,Manager');
    Route::get('fine-tuning/delete/{id}', [FineTuningController::class, 'delete'])->middleware('role:Admin,Manager');
    
    Route::get('fine-tuning/sync/{id}', [FineTuningController::class, 'sync'])->middleware('role:Admin,Manager');

    Route::get('topic', [TopicController::class, 'list'])->name('admin.topic.list');
    Route::get('topic/delete/{id}', [TopicController::class, 'delete']);
    Route::get('topic/create', [TopicController::class, 'createForm']);
    Route::post('topic/create', [TopicController::class, 'create']);
    Route::get('topic/edit/{id}', [TopicController::class, 'editForm']);
    Route::post('topic/edit/{id}', [TopicController::class, 'update']);

    Route::get('user', [UserController::class, 'list'])->middleware('role:Admin');
    Route::get('user/change-password', [UserController::class, 'change_password'])->middleware('role:Admin');
    Route::post('user/update-password', [UserController::class, 'update_password'])->middleware('role:Admin');
    Route::get('user/add', [UserController::class, 'add'])->middleware('role:Admin');
    Route::post('user/create', [UserController::class, 'create'])->middleware('role:Admin');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->middleware('role:Admin');
    Route::post('user/update', [UserController::class, 'update'])->middleware('role:Admin');
    Route::get('user/delete/{id}', [UserController::class, 'delete'])->middleware('role:Admin');

    Route::get('dia-chi/get/{id}', [DMDiaChiController::class, 'getOptions']);
    Route::get('dia-chi/get/{id}/{id1}', [DMDiaChiController::class, 'getOptions1']);
});