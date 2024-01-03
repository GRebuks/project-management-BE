<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'color' => $this->color,
            'created_at' => $this->created_at,
            'board_column_id' => $this->board_column_id,
            'order' => $this->order,
            'due_date' => $this->due_date,
            'completed' => $this->completed,
            'comments' => CommentResource::collection($this->comments->sortByDesc('created_at')),
        ];
    }
}
