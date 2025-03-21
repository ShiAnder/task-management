<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = auth()->user()->tasks()->latest()->get();
        return response()->json(['tasks' => $tasks]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $task = auth()->user()->tasks()->create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Task created successfully!',
            'task' => $task
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        return response()->json(['task' => $task]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $task->update($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully!',
            'task' => $task
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Log the deletion
        Log::info('Task deleted via API', [
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'deleted_at' => now()
        ]);
        
        $task->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully!'
        ]);
    }
    
    /**
     * Toggle task status
     */
    public function toggleStatus(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Prevent changing completed tasks past due date
        if ($task->status === 'Completed' && $task->due_date < now()->format('Y-m-d')) {
            return response()->json([
                'error' => 'Cannot change status of completed tasks past the due date.'
            ], 400);
        }
        
        $newStatus = $task->status === 'Pending' ? 'Completed' : 'Pending';
        
        $task->update([
            'status' => $newStatus
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Task status updated!',
            'status' => $newStatus,
            'task' => $task
        ]);
    }
}