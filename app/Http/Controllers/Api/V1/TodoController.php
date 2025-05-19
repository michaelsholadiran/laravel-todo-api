<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreTodoRequest;
use App\Http\Requests\V1\UpdateTodoRequest;
use App\Http\Resources\V1\TodoCollection;
use App\Http\Resources\V1\TodoResource;
use App\Repositories\Interfaces\V1\TodoRepositoryInterface;
use Illuminate\Http\JsonResponse;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TodoController extends Controller
{
    protected $todoRepository;

    public function __construct(TodoRepositoryInterface $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $todos = Todo::latest()->paginate(10);
        return TodoResource::collection($todos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request): TodoResource
    {
        $validated = $request->validated();
        $todo = Todo::create($validated);
        return new TodoResource($todo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo): TodoResource
    {
        return new TodoResource($todo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, Todo $todo): TodoResource
    {
        $validated = $request->validated();
        $todo->update($validated);
        return new TodoResource($todo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo): Response
    {
        $todo->delete();
        return response()->noContent();
    }
} 