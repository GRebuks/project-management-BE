<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoardRequest;
use App\Http\Resources\Board\BoardProjectCollectionResource;
use App\Http\Resources\Board\BoardProjectResource;
use App\Models\Board;
use App\Models\Project;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $boards = Board::where('project_id', $project->id)->get();

        return new BoardProjectCollectionResource(['boards' => $boards, 'project' => $project]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BoardRequest $request, Project $project)
    {
        $validated = $request->validated();
        $validated['project_id'] = $project->id;

        $board = Board::create($validated);
        return new BoardProjectResource(['project' => $project, 'board' => $board]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, Board $board)
    {
        return new BoardProjectResource(['project' => $project, 'board' => $board]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BoardRequest $request, Project $project, Board $board)
    {
        $validated = $request->validated();

        $board->update($validated);
        return new BoardProjectResource(['project' => $project, 'board' => $board]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, Board $board)
    {
        $board->delete();
        return response()->json(status: 204);
    }
}
