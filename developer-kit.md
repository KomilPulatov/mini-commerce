# Laravel Developer Kit

## Create

```bash
php artisan make:model Product -m
php artisan make:controller Api/ProductController
php artisan make:request StoreProductRequest
php artisan make:resource ProductResource
php artisan make:factory ProductFactory
php artisan make:seeder ProductSeeder
php artisan make:test ProductTest
php artisan make:job ProcessSomething
php artisan make:event SomethingHappened
php artisan make:listener HandleSomething
php artisan make:enum ProductStatus
php artisan make:migration create_products_table
```

## Database

```bash
php artisan migrate
php artisan migrate:rollback
php artisan migrate:reset
php artisan migrate:fresh
php artisan migrate:fresh --seed
php artisan db:seed
```

## Routes / Debugging

```bash
php artisan route:list
php artisan route:list --path=api
php artisan tinker
php artisan about
php artisan config:show
php artisan optimize:clear
```

## Pint

```bash
vendor\bin\pint
```

## API Docs

```bash
composer require knuckleswtf/scribe --dev
php artisan vendor:publish --tag=scribe-config
php artisan scribe:generate
```

## Composer

```bash
composer install
composer update
composer require vendor/package
composer remove vendor/package
composer outdated
```

## Cache / Queue

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan queue:work
php artisan queue:retry all
php artisan queue:flush
```
