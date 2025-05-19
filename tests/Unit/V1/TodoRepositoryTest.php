use App\Models\V1\Todo;
use App\Repositories\Eloquent\V1\TodoRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new TodoRepository(new Todo());
});

test('can get all todos', function () {
    Todo::factory()->count(3)->create();

    $todos = $this->repository->all();

    expect($todos)->toHaveCount(3);
});

test('can find todo by id', function () {
    $todo = Todo::factory()->create();

    $found = $this->repository->find($todo->id);

    expect($found->id)->toBe($todo->id);
});

test('can create todo', function () {
    $data = [
        'title' => 'Test Todo',
        'description' => 'Test Description',
        'status' => false,
        'due_date' => now()->addDays(7)
    ];

    $todo = $this->repository->create($data);

    expect($todo->title)->toBe($data['title'])
        ->and($todo->description)->toBe($data['description'])
        ->and($todo->status)->toBe($data['status']);
});

test('can update todo', function () {
    $todo = Todo::factory()->create();
    $data = ['title' => 'Updated Title'];

    $updated = $this->repository->update($todo->id, $data);

    expect($updated->title)->toBe($data['title']);
});

test('can delete todo', function () {
    $todo = Todo::factory()->create();

    $deleted = $this->repository->delete($todo->id);

    expect($deleted)->toBeTrue()
        ->and(Todo::withTrashed()->find($todo->id))->toBeNull();
});

test('can restore deleted todo', function () {
    $todo = Todo::factory()->create();
    $todo->delete();

    $restored = $this->repository->restore($todo->id);

    expect($restored)->toBeTrue()
        ->and(Todo::find($todo->id))->not->toBeNull();
});

test('can get completed todos', function () {
    Todo::factory()->completed()->count(3)->create();
    Todo::factory()->pending()->count(2)->create();

    $completed = $this->repository->getCompleted();

    expect($completed)->toHaveCount(3)
        ->and($completed->every(fn ($todo) => $todo->status))->toBeTrue();
});

test('can get pending todos', function () {
    Todo::factory()->completed()->count(2)->create();
    Todo::factory()->pending()->count(4)->create();

    $pending = $this->repository->getPending();

    expect($pending)->toHaveCount(4)
        ->and($pending->every(fn ($todo) => !$todo->status))->toBeTrue();
});

test('can get overdue todos', function () {
    Todo::factory()->overdue()->count(3)->create();
    Todo::factory()->pending()->count(2)->create();

    $overdue = $this->repository->getOverdue();

    expect($overdue)->toHaveCount(3)
        ->and($overdue->every(fn ($todo) => 
            !$todo->status && $todo->due_date->isPast()
        ))->toBeTrue();
}); 