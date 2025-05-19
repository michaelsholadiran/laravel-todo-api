<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces\V1;

use App\Models\Todo;
use Illuminate\Pagination\LengthAwarePaginator;

interface TodoRepositoryInterface
{
    /**
     * Get all todos with pagination.
     *
     * @return LengthAwarePaginator<Todo>
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Find a todo by ID.
     */
    public function find(int $id): ?Todo;

    /**
     * Create a new todo.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): Todo;

    /**
     * Update a todo.
     *
     * @param array<string, mixed> $data
     */
    public function update(Todo $todo, array $data): bool;

    /**
     * Delete a todo.
     */
    public function delete(Todo $todo): bool;
} 