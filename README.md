# Todo API

A simple RESTful API for managing todos built with Laravel 12.

## Setup

1. Clone the repository:

    ```bash
    git clone <repository-url>
    cd todo-api
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Configure your environment:

    - Copy `.env.example` to `.env`
    - Update database settings in `.env`

4. Run migrations:

    ```bash
    php artisan migrate
    ```

5. Start the server:
    ```bash
    php artisan serve
    ```

## API Endpoints

### Get All Todos

-   **Method:** GET
-   **URL:** `/api/todos`
-   **Description:** Retrieves a list of all todos.

### Create Todo

-   **Method:** POST
-   **URL:** `/api/todos`
-   **Body:**
    ```json
    {
        "title": "New Todo",
        "description": "Description of the new todo",
        "completed": false
    }
    ```
-   **Description:** Creates a new todo.

### Get Todo by ID

-   **Method:** GET
-   **URL:** `/api/todos/{id}`
-   **Description:** Retrieves a specific todo by its ID.

### Update Todo

-   **Method:** PUT
-   **URL:** `/api/todos/{id}`
-   **Body:**
    ```json
    {
        "title": "Updated Todo",
        "description": "Updated description",
        "completed": true
    }
    ```
-   **Description:** Updates an existing todo.

### Delete Todo

-   **Method:** DELETE
-   **URL:** `/api/todos/{id}`
-   **Description:** Deletes a todo by its ID.

## Testing with Postman

A Postman collection is provided in `postman_collection.json`. Import this file into Postman to test the API endpoints.

1. Open Postman.
2. Click "Import" and select `postman_collection.json`.
3. Set the `base_url` variable to your API's base URL (e.g., `http://localhost:8000`).
4. You can now test all endpoints easily!

## Code Quality Tools

This project uses the following tools to maintain code quality:

-   **Laravel Pint:** For code formatting.
-   **PHP Insights:** For code quality analysis.
-   **Larastan:** For static analysis.

Run these tools using:

```bash
./vendor/bin/pint
./vendor/bin/phpinsights
./vendor/bin/phpstan analyse app
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
