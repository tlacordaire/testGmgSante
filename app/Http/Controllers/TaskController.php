<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTask;
use App\Http\Requests\UpdateTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(User $user)
    {
        return response()->json($user->tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTask $request, User $user)
    {
        $task = new Task();

        $task->name = $request->name;
        $task->description = $request->description;
        $task->user_id = $request->user_id;

        $task->save();

        return response()->json(['message' => 'La tâche a été créée.']);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user, Task $task)
    {
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTask $request, User $user, Task $task)
    {
        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'La tâche a été mise à jour.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user, Task $task)
    {
        $task->delete();

        return response()->json(['message' => 'La tâche a été supprimée.']);
    }

    /**
     * Remove specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyMultiple(Request $request, User $user)
    {
        $idsToDelete = $request->input('ids', []);

        $user->tasks()->whereIn('id', $idsToDelete)->delete();

        return response()->json(['message' => 'Les tâches ont été supprimées.']);
    }
}
