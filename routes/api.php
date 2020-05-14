<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/tasks', 'TasksController@show');

Route::group(['middleware' => 'api'], function(){
    Route::post('/tasks/add_task', function (Request $request) {
        return [
            'task' => [ "id"=> 5, "name"=> "task5", "status"=> false ],
        ];
    });
});
