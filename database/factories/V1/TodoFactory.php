namespace Database\Factories\V1;

use App\Models\V1\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    protected $model = Todo::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->boolean(30), // 30% chance of being completed
            'due_date' => $this->faker->dateTimeBetween('now', '+2 months'),
        ];
    }

    /**
     * Indicate that the todo is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    /**
     * Indicate that the todo is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    /**
     * Indicate that the todo is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
            'due_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }
} 