<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductListResource;
use App\Http\Resources\Api\V1\ProductResource;
use App\Repositories\ProductRepository;
use App\Services\Products\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends Controller
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductService $productService
    ) {}

    public function getList(): JsonResponse
    {
        try {
            $list = $this->productRepository->getList();

            return response()->json(new ProductListResource($list));
        } catch (\Throwable $throwable) {
            Log::debug($throwable->getMessage(), ['exception' => $throwable]);
            abort(500, 'Failed to get products list');
        }
    }

    public function getProduct(int $id): JsonResponse
    {
        try {
            $product = $this->productService->getProductFromCache($id);

            return response()->json(
                data: $product ? new ProductResource($product) : null,
                status: blank($product) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK
            );
        } catch (\Throwable $throwable) {
            Log::debug($throwable->getMessage(), ['exception' => $throwable]);
            abort(500, 'Failed to get a product');
        }
    }
}
