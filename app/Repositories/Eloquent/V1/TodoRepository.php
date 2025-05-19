<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent\V1;

use App\Models\Todo;
use App\Repositories\Interfaces\V1\TodoRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class TodoRepository implements TodoRepositoryInterface
{
    public function __construct(
        protected Todo $model
    ) {
    }

    /**
     * Get all todos with pagination.
     *
     * @return LengthAwarePaginator<Todo>
     */
    public function getAll(): LengthAwarePaginator
    {
        return $this->model->latest()->paginate(10);
    }

    /**
     * Find a todo by ID.
     */
    public function find(int $id): ?Todo
    {
        return $this->model->find($id);
    }

    /**
     * Create a new todo.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): Todo
    {
        return $this->model->create($data);
    }

    /**
     * Update a todo.
     *
     * @param array<string, mixed> $data
     */
    public function update(Todo $todo, array $data): bool
    {
        return $todo->update($data);
    }

    /**
     * Delete a todo.
     */
    public function delete(Todo $todo): bool
    {
        return $todo->delete();
    }
} 