<?php
use Illuminate\Http\Request;

Route::get('/', function () {
    Storage::disk('upyun')->delete('/h44oLFUBUSLFLZ9e6huf2vSccc1qFKqsQfTAphZC.png');
    return view('welcome');
});

Route::post('/image', function (Request $reauest) {
    return Storage::disk('upyun')->put('/', $reauest->file('image'));
});

Route::any('/wechat', 'WeChatController@serve');
Route::get('/index', 'WeChatController@index');
Route::get('/user', 'WeChatController@user');