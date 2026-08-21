<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('serves the Swagger documentation UI', function () {
    $response = $this->get('/docs');

    $response->assertOk();
});

it('serves a valid OpenAPI specification', function () {
    $response = $this->getJson('/docs/spec');

    $response->assertOk()
        ->assertJson([
            'openapi' => '3.0.3',
            'info' => [
                'title' => 'User Service API',
                'version' => '1.0.0',
            ],
        ]);
});

it('authenticates a user and returns an access token', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'token',
            'token_type',
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
        ]);
});

it('allows an authenticated user to retrieve their own profile', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/me');

    $response->assertOk()
        ->assertJson([
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
        ]);
});

it('allows an authenticated user to log out', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token');

    $response = $this->withToken($token->plainTextToken)
        ->postJson('/api/logout');

    $response->assertOk()
        ->assertJson([
            'message' => 'Logged out successfully.',
        ]);
});
