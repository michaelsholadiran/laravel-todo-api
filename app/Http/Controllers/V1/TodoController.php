<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreTodoRequest;
use App\Http\Requests\V1\UpdateTodoRequest;
use App\Http\Resources\V1\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TodoController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $user = request()->get('user');
        $todos = $user->todos()->latest()->paginate(10);
        return TodoResource::collection($todos);
    }

    public function store(StoreTodoRequest $request): TodoResource
    {
        $user = request()->get('user');
        $todo = $user->todos()->create($request->validated());
        return new TodoResource($todo);
    }

    public function show(Todo $todo): TodoResource
    {
        $user = request()->get('user');
        if ($todo->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        return new TodoResource($todo);
    }

    public function update(UpdateTodoRequest $request, Todo $todo): TodoResource
    {
        $user = request()->get('user');
        if ($todo->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        $todo->update($request->validated());
        return new TodoResource($todo);
    }

    public function destroy(Todo $todo): Response
    {
        $user = request()->get('user');
        if ($todo->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        $todo->delete();
        return response()->noContent();
    }
} 