<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $this->service->validateParams($request->type, $request->orderBy);

            return response()->json($this->service->getProducts($request->type, $request->orderBy));
        } catch (Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function createByChunk()
    {
        $count = $this->service->createNewProducts();

        return response()->json($count);
    }

    public function clear()
    {
        $this->service->clearProducts();

        return response()->noContent();
    }
}
