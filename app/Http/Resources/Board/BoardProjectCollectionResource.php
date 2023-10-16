<?php

namespace App\Http\Resources\Board;

use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardProjectCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'boards' => BoardResource::collection($this->resource['boards']),
                'project' => new ProjectResource($this->resource['project']),
            ],
        ];
    }
}
