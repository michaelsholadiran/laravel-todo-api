namespace Database\Seeders\V1;

use App\Models\V1\Todo;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    public function run(): void
    {
        // Create some completed todos
        Todo::factory()
            ->count(5)
            ->completed()
            ->create();

        // Create some pending todos
        Todo::factory()
            ->count(10)
            ->pending()
            ->create();

        // Create some overdue todos
        Todo::factory()
            ->count(3)
            ->overdue()
            ->create();

        // Create some random todos
        Todo::factory()
            ->count(7)
            ->create();
    }
} 