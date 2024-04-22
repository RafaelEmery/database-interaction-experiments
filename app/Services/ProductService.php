<?php

namespace App\Services;

use App\Models\Product;
use App\Enums\QueryParams;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductService
{
    const TYPE_PARAMS = [
        QueryParams::RAW, QueryParams::QUERY_BUILDER, QueryParams::ORM
    ];
    const ORDER_BY_PARAMS = [
        QueryParams::PRICE, QueryParams::NAME, QueryParams::CREATED_AT
    ];

    public function __construct(private string $table = 'products')
    {
    }

    public function validateParams(string $type, string $orderBy): void
    {
        if (!in_array($type, self::TYPE_PARAMS)) {
            throw new Exception("Parâmetro de tipo inválido");
        }
        if (!in_array($orderBy, self::ORDER_BY_PARAMS)) {
            throw new Exception("Parâmetro de ordernação inválido");
        }
    }

    public function getProducts(string $type, string $orderBy): array
    {

        if ($type === QueryParams::RAW) {
            $products = $this->getProductsByRawSql($orderBy);
        }
        if ($type === QueryParams::QUERY_BUILDER) {
            $products = $this->getProductsByQueryBuilder($orderBy);
        }
        if ($type === QueryParams::ORM) {
            $products = $this->getProductsByEloquent($orderBy);
        }

        return $products;
    }

    public function createNewProducts(): int
    {
        $quantity = 1000;
        Product::factory($quantity)->create();

        return Product::all()->count();
    }

    public function clearProducts(): void
    {
        Product::truncate();
    }

    private function getProductsByEloquent(string $orderBy): array
    {
        return Product::query()->orderBy($orderBy, 'desc')
            ->get()
            ->toArray();
    }

    private function getProductsByQueryBuilder(string $orderBy): array
    {
        return DB::table($this->table)->select('*')
            ->orderBy($orderBy, 'desc')
            ->get()
            ->toArray();
    }

    private function getProductsByRawSql(string $orderBy): array
    {
        return DB::select(
            "select * from {$this->table} order by {$orderBy} desc"
        );
    }
}
