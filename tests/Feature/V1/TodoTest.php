<?php

declare(strict_types=1);

namespace Tests\Feature\V1;

use App\Models\V1\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{get, post, put, delete};

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->todo = Todo::factory()->create();
});

test('can list todos', function () {
    // Create some additional todos
    Todo::factory()->count(3)->create();

    get('/api/v1/todos')
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'due_date',
                    'created_at',
                    'updated_at'
                ]
            ],
            'meta' => [
                'total',
                'per_page',
                'current_page',
                'last_page',
                'from',
                'to'
            ]
        ]);
});

test('can create todo', function () {
    $todoData = [
        'title' => 'Test Todo',
        'description' => 'Test Description',
        'status' => 'pending',
        'due_date' => now()->addDays(7)->toDateTimeString()
    ];

    post('/api/v1/todos', $todoData, [
        'Accept' => 'application/json',
    ])
        ->assertCreated()
        ->assertJson([
            'data' => [
                'title' => $todoData['title'],
                'description' => $todoData['description'],
                'status' => $todoData['status']
            ]
        ]);

    $this->assertDatabaseHas('todos', [
        'title' => $todoData['title'],
        'description' => $todoData['description'],
    ]);
});

test('can show todo', function () {
    get("/api/v1/todos/{$this->todo->id}")
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $this->todo->id,
                'title' => $this->todo->title,
                'description' => $this->todo->description,
                'status' => $this->todo->status
            ]
        ]);
});

test('can update todo', function () {
    $updateData = [
        'title' => 'Updated Todo',
        'description' => $this->todo->description,
        'status' => 'in_progress',
        'due_date' => $this->todo->due_date?->toDateTimeString()
    ];

    put("/api/v1/todos/{$this->todo->id}", $updateData, [
        'Accept' => 'application/json',
    ])
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $this->todo->id,
                'title' => $updateData['title'],
                'status' => $updateData['status']
            ]
        ]);

    $this->assertDatabaseHas('todos', [
        'id' => $this->todo->id,
        'title' => $updateData['title'],
        'status' => $updateData['status']
    ]);
});

test('can delete todo', function () {
    delete("/api/v1/todos/{$this->todo->id}", [], [
        'Accept' => 'application/json',
    ])
        ->assertNoContent();

    $this->assertSoftDeleted('todos', [
        'id' => $this->todo->id
    ]);
});

test('validates required fields when creating todo', function () {
    post('/api/v1/todos', [], [
        'Accept' => 'application/json',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title', 'description', 'status']);
});

test('validates title length when creating todo', function () {
    post('/api/v1/todos', [
        'title' => 'ab', // too short
        'description' => 'Test Description',
        'status' => 'pending'
    ], [
        'Accept' => 'application/json',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
});

test('validates status values when creating todo', function () {
    post('/api/v1/todos', [
        'title' => 'Test Todo',
        'description' => 'Test Description',
        'status' => 'invalid_status'
    ], [
        'Accept' => 'application/json',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});

test('validates due date when creating todo', function () {
    post('/api/v1/todos', [
        'title' => 'Test Todo',
        'description' => 'Test Description',
        'status' => 'pending',
        'due_date' => now()->subDay()->toDateTimeString() // past date
    ], [
        'Accept' => 'application/json',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['due_date']);
});

test('returns 404 for non-existent todo', function () {
    get('/api/v1/todos/99999')
        ->assertNotFound();
}); 