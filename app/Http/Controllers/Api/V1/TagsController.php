<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TagResource;
use App\Services\Products\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TagsController extends Controller
{
    public function __construct(
        private readonly TagService $tagService,
    ) {}

    public function getPopularList(): JsonResponse
    {
        try {
            $popularTags = $this->tagService->getMostPopularList();

            return response()->json(TagResource::collection($popularTags));
        } catch (\Throwable $throwable) {
            Log::debug($throwable->getMessage(), ['exception' => $throwable]);
            abort(500, 'Failed to load popular tags list');
        }
    }
}
