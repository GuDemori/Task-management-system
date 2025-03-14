<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Subtask::with('task')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,completed',
            'task_id' => 'required|exists:tasks,id'
        ]);

        $subtask = Subtask::create($validated);
        return response()->json($subtask, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subtask $subtask)
    {
        return response()->json($subtask->load('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subtask $subtask)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'sometimes|in:pending,completed'
        ]);

        $subtask->update($validated);
        return response()->json($subtask);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subtask $subtask)
    {
        $subtask->delete();
        return response()->json(null, 204);
    }
}
