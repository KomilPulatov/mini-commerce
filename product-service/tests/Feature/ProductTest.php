<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists products', function () {
    Product::factory()->count(3)->create();

    $this->getJson('/api/products')
        ->assertSuccessful()
        ->assertJsonCount(3, 'data');
});

it('creates a product', function () {
    $payload = [
        'name' => 'iPhone 17',
        'description' => 'Latest iPhone',
        'price' => 99900,
        'stock' => 10,
        'is_active' => true,
    ];

    $this->postJson('/api/products', $payload)
        ->assertCreated()
        ->assertJsonPath('data.name', 'iPhone 17');

    $this->assertDatabaseHas('products', [
        'name' => 'iPhone 17',
        'price' => 99900,
        'stock' => 10,
    ]);
});

it('requires valid product data when creating a product', function () {
    $this->postJson('/api/products', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name',
            'price',
            'stock',
        ]);
});

it('shows a product', function () {
    $product = Product::factory()->create();

    $this->getJson("/api/products/{$product->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.id', $product->id);
});

it('returns 404 when product does not exist', function () {
    $this->getJson('/api/products/999999')
        ->assertNotFound();
});

it('updates a product', function () {
    $product = Product::factory()->create([
        'name' => 'Old Name',
        'price' => 10000,
    ]);

    $this->patchJson("/api/products/{$product->id}", [
        'name' => 'New Name',
        'price' => 20000,
    ])
        ->assertSuccessful()
        ->assertJsonPath('data.name', 'New Name')
        ->assertJsonPath('data.price', 20000);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'New Name',
        'price' => 20000,
    ]);
});

it('deletes a product', function () {
    $product = Product::factory()->create();

    $this->deleteJson("/api/products/{$product->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});

it('searches products by name', function () {
    Product::factory()->create(['name' => 'iPhone 17']);
    Product::factory()->create(['name' => 'MacBook Pro']);

    $this->getJson('/api/products?search=iPhone')
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'iPhone 17');
});

it('filters products by price range', function () {
    Product::factory()->create(['name' => 'Cheap', 'price' => 1000]);
    Product::factory()->create(['name' => 'Middle', 'price' => 5000]);
    Product::factory()->create(['name' => 'Expensive', 'price' => 10000]);

    $this->getJson('/api/products?min_price=2000&max_price=6000')
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Middle');
});

it('filters products by active status', function () {
    Product::factory()->create([
        'name' => 'Active Product',
        'is_active' => true,
    ]);

    Product::factory()->create([
        'name' => 'Inactive Product',
        'is_active' => false,
    ]);

    $this->getJson('/api/products?is_active=false')
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Inactive Product');
});

it('sorts products by price', function () {
    Product::factory()->create([
        'name' => 'Expensive',
        'price' => 10000,
    ]);

    Product::factory()->create([
        'name' => 'Cheap',
        'price' => 1000,
    ]);

    $this->getJson('/api/products?sort=price&direction=asc')
        ->assertSuccessful()
        ->assertJsonPath('data.0.name', 'Cheap')
        ->assertJsonPath('data.1.name', 'Expensive');
});

it('paginates products', function () {
    Product::factory()->count(20)->create();

    $this->getJson('/api/products?per_page=10')
        ->assertSuccessful()
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);
});
