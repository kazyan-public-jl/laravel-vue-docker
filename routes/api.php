<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'api'], function(){
    Route::get('/tasks', function (Request $request) {
        return [
            'tasks' => [
                [ "id"=> 1, "name"=> "task1", "status"=> true ],
                [ "id"=> 2, "name"=> "task2", "status"=> true ],
                [ "id"=> 3, "name"=> "task3", "status"=> false ],
                [ "id"=> 4, "name"=> "task4", "status"=> false ],
            ]
        ];
    });
    Route::post('/tasks/add_task', function (Request $request) {
        return [
            'task' => [ "id"=> 5, "name"=> "task5", "status"=> false ],
        ];
    });
});
