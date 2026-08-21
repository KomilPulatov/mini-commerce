<?php

namespace App\Queries;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductQuery
{
    public function __construct(
        private readonly array $filters,
    ) {}

    public function paginate(): LengthAwarePaginator
    {
        $query = Product::query();

        if (isset($this->filters['search'])) {
            $query->where(
                'name',
                'like',
                '%' . $this->filters['search'] . '%'
            );
        }

        if (isset($this->filters['min_price'])) {
            $query->where('price', '>=', $this->filters['min_price']);
        }

        if (isset($this->filters['max_price'])) {
            $query->where('price', '<=', $this->filters['max_price']);
        }

        if (isset($this->filters['is_active'])) {
            $query->where(
                'is_active',
                $this->filters['is_active']
            );
        }

        $query->orderBy(
            $this->filters['sort'] ?? 'created_at',
            $this->filters['direction'] ?? 'desc'
        );

        return $query
            ->paginate($this->filters['per_page'] ?? 15)
            ->withQueryString();
    }
}
