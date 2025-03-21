<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllForUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Task::where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }
    
    public function findById(int $id): ?Task
    {
        return Task::find($id);
    }
    
    public function create(array $data): Task
    {
        return Task::create($data);
    }
    
    public function update(Task $task, array $data): bool
    {
        return $task->update($data);
    }
    
    public function delete(Task $task): bool
    {
        // Log the deletion
        Log::info('Task deleted', [
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'deleted_at' => now()
        ]);
        
        return $task->delete();
    }
    
    public function toggleStatus(Task $task): Task
    {
        $task->update([
            'status' => $task->status === 'Pending' ? 'Completed' : 'Pending'
        ]);
        
        return $task->fresh();
    }
}