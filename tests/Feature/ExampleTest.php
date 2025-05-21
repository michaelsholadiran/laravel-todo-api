<?php

declare(strict_types=1);

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});




❌ Messy API:

GET /getUserInfo?type=admin&details=true

✅ Clean API:

GET /users/admin?includeDetails=true