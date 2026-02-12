<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_tasks(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_show_single_task(): void
    {
        $task = Task::factory()->create([
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => TaskStatus::PENDING->value,
        ]);

        $response = $this->getJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $task->id,
                'title' => 'Test Task',
                'description' => 'Test Description',
                'status' => TaskStatus::PENDING->value,
            ]);
    }

    public function test_can_create_task(): void
    {
        $taskData = [
            'title' => 'New Task',
            'description' => 'New Description',
            'status' => TaskStatus::PENDING->value,
        ];

        $response = $this->postJson('/api/v1/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'title' => 'New Task',
                'description' => 'New Description',
                'status' => TaskStatus::PENDING->value,
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'description' => 'New Description',
            'status' => TaskStatus::PENDING->value,
        ]);
    }

    public function test_can_update_task(): void
    {
        $task = Task::factory()->create([
            'title' => 'Original Title',
            'status' => TaskStatus::PENDING->value,
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => TaskStatus::IN_PROGRESS->value,
        ];

        $response = $this->putJson("/api/v1/tasks/{$task->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'Updated Title',
                'description' => 'Updated Description',
                'status' => TaskStatus::IN_PROGRESS->value,
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => TaskStatus::IN_PROGRESS->value,
        ]);
    }

    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(204)
            ->assertNoContent();

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_title_is_required(): void
    {
        $taskData = [
            'description' => 'Description without title',
        ];

        $response = $this->postJson('/api/v1/tasks', $taskData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_status_must_be_valid_enum(): void
    {
        $taskData = [
            'title' => 'Test Task',
            'status' => 'invalid_status',
        ];

        $response = $this->postJson('/api/v1/tasks', $taskData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    public function test_can_not_show_nonexistent_task(): void
    {
        $response = $this->getJson('/api/v1/tasks/999');

        $response->assertStatus(404);
    }
}
