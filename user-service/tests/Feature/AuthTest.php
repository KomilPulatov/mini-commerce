<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('swagger documentation ui is accessible', function () {
    $response = $this->get('/docs');

    $response->assertStatus(200);
});

test('openapi specification json is accessible and valid', function () {
    $response = $this->get('/docs/spec');

    $response->assertStatus(200)
        ->assertJson([
            'openapi' => '3.0.3',
            'info' => [
                'title' => 'User Service API',
                'version' => '1.0.0',
            ],
        ]);
});

test('user can authenticate and receive a token', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'token',
            'token_type',
            'user' => ['id', 'name', 'email', 'created_at', 'updated_at'],
        ]);
});

test('authenticated user can fetch own profile', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->getJson('/api/me');

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
        ]);
});

test('authenticated user can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token');

    $response = $this->withToken($token->plainTextToken)->postJson('/api/logout');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Logged out successfully.',
        ]);
});
