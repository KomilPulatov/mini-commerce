<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a guest to register a new user', function () {
    $payload = [
        'name' => 'Alice Smith',
        'email' => 'alice@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->postJson('/api/users', $payload);

    $response->assertCreated()
        ->assertJson([
            'data' => [
                'name' => 'Alice Smith',
                'email' => 'alice@example.com',
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'alice@example.com',
    ]);
});

it('allows an authenticated user to view their own details', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->getJson("/api/users/{$user->id}");

    $response->assertOk()
        ->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
});

it('allows an authenticated user to update their profile', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->patchJson("/api/users/{$user->id}", [
            'name' => 'New Name',
        ]);

    $response->assertOk()
        ->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => 'New Name',
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'New Name',
    ]);
});

it('allows an authenticated user to delete their account', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->deleteJson("/api/users/{$user->id}");

    $response->assertNoContent();

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});
