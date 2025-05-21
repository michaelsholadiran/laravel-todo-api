<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreTodoRequest;
use App\Http\Requests\V1\UpdateTodoRequest;
use App\Http\Resources\V1\TodoCollection;
use App\Http\Resources\V1\TodoResource;
use App\Repositories\Interfaces\V1\TodoRepositoryInterface;
use Illuminate\Http\JsonResponse;
use App\Models\V1\Todo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TodoController extends Controller
{
    public function __construct(
        protected TodoRepositoryInterface $todoRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->get('user');
        $todos = $user->todos()->latest()->paginate(10);
        return TodoResource::collection($todos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request): TodoResource
    {
        $user = $request->get('user');
        $validated = $request->validated();
        $todo = $user->todos()->create($validated);
        return new TodoResource($todo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Todo $todo): TodoResource
    {
        $user = $request->get('user');
        if ($todo->user_id !== $user->id) {
            abort(404);
        }
        return new TodoResource($todo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, Todo $todo): TodoResource
    {
        $user = $request->get('user');
        if ($todo->user_id !== $user->id) {
            abort(404);
        }
        $validated = $request->validated();
        $todo->update($validated);
        return new TodoResource($todo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Todo $todo): Response
    {
        $user = $request->get('user');
        if ($todo->user_id !== $user->id) {
            abort(404);
        }
        $todo->delete();
        return response()->noContent();
    }

    public function destroyAll(Request $request): Response
    {
        $user = $request->get('user');
        $deleted = $this->todoRepository->deleteAllForUser($user);
        return response()->noContent()->header('X-Todos-Deleted', $deleted);
    }
} 