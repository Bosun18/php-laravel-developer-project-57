<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TaskStatus;

class TaskStatusTest extends TestCase
{
    private User $user;
    private TaskStatus $taskStatus;
    private string $nameForTaskStatus;
    private string $nameForTaskStatusUpdate;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->taskStatus = TaskStatus::factory()->create();
        $this->nameForTaskStatus = TaskStatus::factory()->create();
        $this->nameForTaskStatusUpdate = TaskStatus::factory()->create();
    }


    public function testIndex(): void
    {
        $response = $this->get(route('task_statuses.index'));

        $response->assertStatus(200);
    }

    public function testCreate(): void
    {
        $response = $this->actingAs($this->user)->get(route('task_statuses.create'));

        $response->assertOk();
    }

    public function testStore(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->post(route('task_statuses.store'), [
                'name' => $this->nameForTaskStatus
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('task_statuses', ['name' => $this->nameForTaskStatus]);
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
        $response = $this
            ->actingAs($this->user)
            ->patch(route('task_statuses.update', ['task_status' => $this->taskStatus]), [
                'name' => $this->nameForTaskStatusUpdate
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('task_statuses', ['name' => $this->nameForTaskStatusUpdate]);
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
}
