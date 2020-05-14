<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $tasks = \App\Tasks::all();
        return $tasks;
    }

}
