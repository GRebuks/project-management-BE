<?php

namespace App\Http\Resources\Board;

use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardProjectResource extends JsonResource
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
                'board' => new BoardResource($this->resource['board']),
                'project' => new ProjectResource($this->resource['project']),
            ],
        ];
    }
}
