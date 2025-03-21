<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function getAllForUser(int $userId, int $perPage = 5): LengthAwarePaginator;
    public function findById(int $id): ?Task;
    public function create(array $data): Task;
    public function update(Task $task, array $data): bool;
    public function delete(Task $task): bool;
    public function toggleStatus(Task $task): Task;
}