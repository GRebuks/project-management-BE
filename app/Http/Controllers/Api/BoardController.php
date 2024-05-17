<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoardColumnRequest;
use App\Http\Requests\BoardRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\BoardColumnResource;
use App\Http\Resources\BoardResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\TaskResource;
use App\Models\Board;
use App\Models\BoardColumn;
use App\Models\Comment;
use App\Models\Task;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $boardResource = new BoardResource($board);
        $boardData = $boardResource->toArray(request());
        $etag = md5(json_encode($boardData));
        if (request()->header('If-None-Match') === $etag) {
            return response()->json([], 304);
        }
        return response()->json(['data' => new BoardResource($board), "etag" => $etag])->header('ETag', $etag);
        //return new BoardResource($board);
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

    // COMMENTS
    public function storeComment(CommentRequest $request, Workspace $workspace, Board $board, BoardColumn $boardColumn, Task $task)
    {
        $validated = $request->validated();
        $validated['task_id'] = $task->id;
        $validated['user_id'] = auth()->id();
        $comment = Comment::create($validated);
        return new CommentResource($comment);
    }

    public function updateComment(CommentRequest $request, Workspace $workspace, Board $board, BoardColumn $boardColumn, Task $task, Comment $comment)
    {
        $validated = $request->validated();
        $comment->update($validated);
        return new CommentResource($comment);
    }

    public function destroyComment(Workspace $workspace, Board $board, BoardColumn $boardColumn, Task $task, Comment $comment)
    {
        $comment->delete();
        return response()->json(status:204);
    }

    public function saveBoardChanges(string $workspaceId, string $boardId)
    {
        $board = Board::with('boardColumns.tasks')->findOrFail($boardId);
        $requestData = request()->all()['data'];
        //$board->update($requestData['board']);

        // Loop through columns and update attributes
        if ($requestData['columns']) {
            foreach ($requestData['columns'] as $columnData) {
                $column = $board->boardColumns()->find($columnData['id']);
                $column->update($columnData);

                if ($columnData['tasks']) {
                    // Loop through tasks and update attributes
                    foreach ($columnData['tasks'] as $taskData) {
                        $task = $column->tasks()->find($taskData['id']);
                        $task->order = $taskData['order'];
                        $task->update($taskData);
                    }
                }
            }
        }

        // You can return a response indicating success if needed
        return response()->json(['message' => 'Board changes saved successfully']);
    }

    public function reorderTask(Request $request) {
        try {
            $task = Task::findOrFail($request->taskId);
            $task->board_column_id = $request->newColumnId;
            $task->save();

            return response()->json(['message' => 'Task updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating task'], 500);
        }
    }

    public function updateColumnPositions($boardId, $columnData)
    {
        $columnIds = collect($columnData)->pluck('id')->toArray();

        // Update the order attribute for columns
        DB::table('columns')
            ->whereIn('id', $columnIds)
            ->update(['order' => DB::raw('FIND_IN_SET(id, ?)'), 'updated_at' => now()], [implode(',', $columnIds)]);

        // Optionally, return a response or perform additional actions
        return response()->json(['message' => 'Column positions updated successfully']);
    }
}
