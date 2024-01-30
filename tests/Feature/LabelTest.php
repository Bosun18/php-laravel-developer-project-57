<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;

class LabelTest extends TestCase
{
    private User $user;
    private Label $label;
    private string $nameForLabel;
    private string $nameForUpdateLabel;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->label = Label::factory()->create();
        $this->nameForLabel = Label::factory()->create();
        $this->nameForUpdateLabel = Label::factory()->create();
    }

    public function testIndex(): void
    {
        $response = $this->get(route('labels.index'));

        $response->assertStatus(200);
    }

    public function testCreate(): void
    {
        $response = $this->actingAs($this->user)->get(route('labels.create'));

        $response->assertOk();
    }

    public function testStore(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->post(route('labels.store'), [
                'name' => $this->nameForLabel
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('labels', ['name' => $this->nameForLabel]);
        $response->assertRedirect(route('labels.index'));
    }

    public function testEdit(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('labels.edit', ['label' => $this->label]));

        $response->assertOk();
    }

    public function testUpdate(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->patch(route('labels.update', ['label' => $this->label]), [
                'name' => $this->nameForUpdateLabel
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('labels', ['name' => $this->nameForUpdateLabel]);
        $response->assertRedirect(route('labels.index'));
    }

    public function testDestroy(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->delete(route('labels.destroy', ['label' => $this->label]));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('labels', ['id' => $this->label->id]);
        $response->assertRedirect(route('labels.index'));
    }

    public function testDestroyLabelWithAssociatedTask(): void
    {
        $label = Label::factory()->create();
        $taskStatus = TaskStatus::factory()->create();
        $task = Task::factory()->create([
            'status_id' => $taskStatus->id,
        ]);
        $task->labels()->attach($label);

        $response = $this->actingAs($this->user)
            ->delete(route('labels.destroy', ['label' => $label]));
        $this->assertDatabaseHas('labels', ['id' => $label->id]);
        $response->assertStatus(302);
    }
}
