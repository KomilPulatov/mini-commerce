<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can register a new user', function () {
    $payload = [
        'name' => 'Alice Smith',
        'email' => 'alice@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->postJson('/api/users', $payload);

    $response->assertStatus(201)
        ->assertJson([
            'data' => [
                'name' => 'Alice Smith',
                'email' => 'alice@example.com',
            ],
        ]);

    $this->assertDatabaseHas('users', ['email' => 'alice@example.com']);
});

test('authenticated user can view user details', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->getJson("/api/users/{$user->id}");

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
});

test('authenticated user can update profile', function () {
    $user = User::factory()->create(['name' => 'Old Name']);

    $response = $this->actingAs($user, 'sanctum')->patchJson("/api/users/{$user->id}", [
        'name' => 'New Name',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => 'New Name',
            ],
        ]);

    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name']);
});

test('authenticated user can delete user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/users/{$user->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});
