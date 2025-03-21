<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskRepository;
    
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->middleware('auth');
        $this->taskRepository = $taskRepository;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = $this->taskRepository->getAllForUser(auth()->id(), 5); // 10 tasks per page
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        
        $this->taskRepository->create($data);
        
        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        
        $this->taskRepository->update($task, $request->validated());
        
        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        
        $this->taskRepository->delete($task);
        
        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }
    
    /**
     * Toggle task status
     */
    public function toggleStatus(Task $task)
    {
        $this->authorize('update', $task);
        
        // Prevent changing completed tasks past due date
        if ($task->status === 'Completed' && $task->due_date < now()->format('Y-m-d')) {
            if (request()->ajax()) {
                return response()->json([
                    'error' => 'Cannot change status of completed tasks past the due date.'
                ]);
            }
            
            return redirect()->route('tasks.index')
                ->with('error', 'Cannot change status of completed tasks past the due date.');
        }
        
        $updatedTask = $this->taskRepository->toggleStatus($task);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task status updated!',
                'status' => $updatedTask->status
            ]);
        }
        
        return redirect()->route('tasks.index')
            ->with('success', 'Task status updated!');
    }
}