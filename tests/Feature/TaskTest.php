<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TaskStatus;
use App\Models\Task;

class TaskTest extends TestCase
{
    private User $user;
    private Task $task;
    private array $newTaskData;
    private array $taskDataForUpdate;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->user = User::factory()->create();
        $this->task = Task::factory()->create([
            'created_by_id' => $this->user->id,
        ]);

        $this->newTaskData = Task::factory()->make()->only([
            'name',
            'description',
            'status_id',
            'assigned_to_id',
        ]);

        $this->taskDataForUpdate = Task::factory()->make()->only([
            'name',
            'description',
            'status_id',
            'assigned_to_id',
        ]);
    }

    public function testIndex(): void
    {
        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
    }

    public function testCreate(): void
    {
        $response = $this->actingAs($this->user)->get(route('tasks.create'));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function testStore(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->post(route('tasks.store'), $this->newTaskData);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('tasks', $this->newTaskData);
        $response->assertRedirect(route('tasks.index'));
    }

    public function testShow(): void
    {
        $response = $this->get(route('tasks.show', $this->task));
        $response->assertOk();
        $response->assertSee($this->task->name);
    }

    public function testEdit(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('tasks.edit', ['task' => $this->task]));

        $response->assertOk();
    }

    /**
     * @throws \JsonException
     */
    public function testUpdate(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->patch(route('tasks.update', ['task' => $this->task]), $this->taskDataForUpdate);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('tasks', $this->taskDataForUpdate);
        $response->assertRedirect(route('tasks.index'));
    }

    /**
     * @throws \JsonException
     */
    public function testDestroy(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->delete(route('tasks.destroy', ['task' => $this->task]));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('tasks', ['id' => $this->task->id]);
        $response->assertRedirect(route('tasks.index'));
    }
}
