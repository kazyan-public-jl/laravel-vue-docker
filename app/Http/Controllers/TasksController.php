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
        $tasks = Tasks::orderBy('order', 'asc')->get();
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
            \Log::info('empty tasks for update.');
            return [];
        }
        $updateTasks = [];
        foreach ($postTasks as $key => $postTask) {
            if (!isset($postTask['id'])) {
                // 新規追加の対応
                if (empty($postTask['name'])) {
                    // name 未入力のため skip
                    \Log::info('try to empty name, skip.');
                    continue;
                }
                $checkTask = Tasks::where([ 'name' => $postTask['name'] ])->first();
                if (!is_null($checkTask)) {
                    // すでに同じ名前のタスクがあるので追加できない
                    // Conflict
                    $message = $postTask['name'] . ' is already exists';
                    \Log::info($message);
                    return $this->reportError($postTask, $postTask['name'] . ' is already exists.', 409);
                }
                // 新規タスクモデルを作成
                $updateTask = new Tasks;
            } else {
                // 既存レコードの更新
                $updateTask = Tasks::find($postTask['id']);
                if (is_null($updateTask)) {
                    // 指定されたIDが見つからないため skip
                    \Log::info($postTask['id'] . ' is not found, skip.');
                    continue;
                }
            }
            $updateTask->name = $postTask['name'];
            $updateTask->order = empty($postTask['order']) ? Tasks::all()->count()+1 : $postTask['order'];
            $updateTask->status = empty($postTask['status']) ? false : $postTask['status'];
            $updateTask->save();
            \Log::info('updateTask:' . $updateTask);
            $updateTasks[]= $updateTask;
        }
        return ['tasks' => $updateTasks];
    }

    /**
     * Tasksインスタンスの削除
     *
     * @param  Request  $request
     * @return Response
     */
    public function delete(Request $request)
    {
        // リクエストのバリデート処理…
        try {
            return $this->deleteTask($request);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    private function deleteTask(Request $request)
    {
        \Log::info($request);
        $postTasks = $request->tasks;
        if(is_null($postTasks)) {
            \Log::info('empty tasks for delete.');
            return [];
        }
        $deleteTasks = [];
        foreach ($postTasks as $key => $postTask) {
            if (!isset($postTask['id'])) {
                \Log::info('column "id" is not exists.');
                continue;
            }
            $deleteTask = Tasks::find($postTask['id']);
            if (is_null($deleteTask)) {
                // Not Found
                $message = $postTask['id'] . ' is not found.';
                return $this->reportError($postTasks, $message, 404);
            }
            \Log::info('delete: ' . $deleteTask->id);
            $deleteTask->delete();
            $deleteTasks[]= $deleteTask;
        }
        // オーダー振り直し
        $newTasks = Tasks::all()->sortBy('order')->map(function ($task, $index){
            $task['order'] = $index+1;
            $task->save();
            return $task;
        });
        return ['tasks' => $newTasks];
    }

    private function reportError($data, string $message, int $status)
    {
        $report = [
            'data' => $data,
            'message' => $message,
            'status' => $status
        ];
        \Log::error($report);
        return response($report, $status);
    }
}
