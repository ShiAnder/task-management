<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends Feature
{
    use RefreshDatabase;

    public function test_tasks_page_is_displayed(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/tasks');
        
        $response->assertStatus(200);
    }
    
    public function test_user_can_create_task(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task description with more than 10 characters',
            'due_date' => now()->addDays(1)->format('Y-m-d'),
        ]);
        
        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $user->id,
        ]);
    }
    
    public function test_user_cannot_create_task_with_past_due_date(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task description with more than 10 characters',
            'due_date' => now()->subDays(1)->format('Y-m-d'),
        ]);
        
        $response->assertSessionHasErrors('due_date');
    }
    
    public function test_user_can_update_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Original Title',
        ]);
        
        $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
            'title' => 'Updated Title',
            'description' => 'This is an updated task description',
            'due_date' => now()->addDays(1)->format('Y-m-d'),
            'status' => 'Pending',
        ]);
        
        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title',
        ]);
    }
    
    public function test_user_cannot_update_others_task(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user1->id,
        ]);
        
        $response = $this->actingAs($user2)->put("/tasks/{$task->id}", [
            'title' => 'Updated by Another User',
            'description' => 'This should not work',
            'due_date' => now()->addDays(1)->format('Y-m-d'),
        ]);
        
        $response->assertStatus(403);
    }
    
    public function test_user_can_delete_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);
        
        $response = $this->actingAs($user)->delete("/tasks/{$task->id}");
        
        $response->assertRedirect('/tasks');
        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    }
}