<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use \App\Tasks;

class TasksController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function read()
    {
        $tasks = Tasks::all();
        return $tasks;
    }

    /**
     * 新しいflightインスタンスの生成
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // リクエストのバリデート処理…
        try {
            //code...
            return $this->updateTask($request);
        } catch (\Throwable $th) {
            //throw $th;
            throw $th;
        }
    }
    /**
     * タスクの更新
     */
    private function updateTask(Request $request)
    {
        $postTasks = $request->tasks;
        if(is_null($postTasks)) {
            return [];
        }
        $updateTasks = [];
        foreach ($postTasks as $key => $postTask) {
            if (!isset($postTask['id'])) {
                // 新規追加の対応
                if (empty($postTask['name'])) {
                    // name 未入力のため skip
                    continue;
                }
                $updateTask = new Tasks;
            } else {
                $updateTask = Tasks::find($postTask['id']);
                if (is_null($updateTask)) {
                    // 指定されたIDが見つからないため skip
                    continue;
                }
            }
            $updateTask->name = $postTask['name'];
            $updateTask->order = empty($postTask['order']) ? Tasks::all()->count()+1 : $postTask['order'];
            $updateTask->status = empty($postTask['status']) ? false : $postTask['status'];
            $updateTask->save();
            $updateTasks[]= $updateTask;
        }
        return ['tasks' => $updateTasks];
    }
}
