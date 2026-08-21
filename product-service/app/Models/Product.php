<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'description',
    'price',
    'stock',
    'is_active',
])]
#[SoftDeletes]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
