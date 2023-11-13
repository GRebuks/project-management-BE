<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoardColumnRequest;
use App\Http\Requests\BoardRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\BoardColumnResource;
use App\Http\Resources\BoardResource;
use App\Http\Resources\TaskResource;
use App\Models\Board;
use App\Models\BoardColumn;
use App\Models\Task;
use App\Models\Workspace;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Workspace $workspace)
    {
        return BoardResource::collection($workspace->boards);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BoardRequest $request, Workspace $workspace)
    {
        $validated = $request->validated();
        $validated['workspace_id'] = $workspace->id;
        $board = Board::create($validated);
        return new BoardResource($board);
    }

    /**
     * Display the specified resource.
     */
    public function show(Workspace $workspace, Board $board)
    {
        return new BoardResource($board);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BoardRequest $request, Workspace $workspace, Board $board)
    {
        $validated = $request->validated();
        $board->update($validated);
        return new BoardResource($board);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workspace $workspace, Board $board)
    {
        $board->delete();
        return response()->json(status:204);
    }

    public function storeBoardColumn(BoardColumnRequest $request, Workspace $workspace, Board $board)
    {
        $validated = $request->validated();
        $validated['board_id'] = $board->id;
        $boardColumn = BoardColumn::create($validated);
        return new BoardColumnResource($boardColumn);
    }
    public function updateBoardColumn(BoardColumnRequest $request, Workspace $workspace, Board $board, BoardColumn $boardColumn)
    {
        $validated = $request->validated();
        $boardColumn->update($validated);
        return new BoardColumnResource($boardColumn);
    }

    public function destroyBoardColumn(Workspace $workspace, Board $board, BoardColumn $boardColumn)
    {
        $boardColumn->delete();
        return response()->json(status:204);
    }
    public function storeTask(TaskRequest $request, Workspace $workspace, Board $board, BoardColumn $boardColumn)
    {
        $validated = $request->validated();
        $validated['board_column_id'] = $boardColumn->id;
        $task = Task::create($validated);
        return new TaskResource($task);
    }
    public function updateTask(TaskRequest $request, Workspace $workspace, Board $board, BoardColumn $boardColumn, Task $task)
    {
        $validated = $request->validated();
        $task->update($validated);
        return new TaskResource($task);
    }

    public function destroyTask(Workspace $workspace, Board $board, BoardColumn $boardColumn, Task $task)
    {
        $task->delete();
        return response()->json(status:204);
    }

}
