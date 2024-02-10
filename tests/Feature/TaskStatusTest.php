<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TaskStatus;
use App\Models\Task;

class TaskStatusTest extends TestCase
{
    private User $user;
    private TaskStatus $taskStatus;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->user = User::factory()->create();
        $this->taskStatus = TaskStatus::factory()->create();
    }


    public function testIndex(): void
    {
        $response = $this->get(route('task_statuses.index'));

        $response->assertStatus(200);
    }

    public function testCreate(): void
    {
        $response = $this->actingAs($this->user)->get(route('task_statuses.create'));

        $response->assertStatus(200);
    }

    public function testStore(): void
    {
        $name = TaskStatus::factory()->make()->name;
        $response = $this
            ->actingAs($this->user)
            ->post(route('task_statuses.store'), [
                'name' => $name
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('task_statuses', ['name' => $name]);
        $response->assertRedirect(route('task_statuses.index'));
    }

    public function testEdit(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('task_statuses.edit', ['task_status' => $this->taskStatus]));

        $response->assertOk();
    }

    public function testUpdate(): void
    {
        $nameUpdate = TaskStatus::factory()->make()->name;
        $response = $this
            ->actingAs($this->user)
            ->patch(route('task_statuses.update', ['task_status' => $this->taskStatus]), [
                'name' => $nameUpdate
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('task_statuses', ['name' => $nameUpdate]);
        $response->assertRedirect(route('task_statuses.index'));
    }

    public function testDestroy(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->delete(route('task_statuses.destroy', ['task_status' => $this->taskStatus]));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('labels', ['id' => $this->taskStatus->id]);
        $response->assertRedirect(route('task_statuses.index'));
    }

    public function testDestroyTaskStatusWithAssociatedTasks(): void
    {
        $taskStatus = TaskStatus::factory()->create();
        Task::factory()->create([
            'status_id' => $taskStatus->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('task_statuses.destroy', ['task_status' => $taskStatus]));

        $this->assertDatabaseHas('task_statuses', ['id' => $taskStatus->id]);
        $response->assertStatus(302);
    }
}
