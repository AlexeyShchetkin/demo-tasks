<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = Task::all();
        return TaskResource::collection($tasks)->response();
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? TaskStatus::PENDING->value,
        ]);

        return (new TaskResource($task))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Task $task): JsonResponse
    {
        return (new TaskResource($task))->response();
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $validated = $request->validated();
        $task->update($validated);

        return (new TaskResource($task))->response();
    }

    public function destroy(Task $task): JsonResponse|Response
    {
        $task->delete();

        return response()->noContent();
    }
}
