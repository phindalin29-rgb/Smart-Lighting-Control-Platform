<?php

namespace Tests\Feature\Home;

use App\Models\Home;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_are_redirected(): void
    {
        $this->get(route('homes.index'))
            ->assertRedirect(route('login'));
    }

    public function test_index_displays_homes(): void
    {
        $user = User::factory()->create();
        Home::create(['user_id' => $user->id, 'name' => 'My Home']);

        $this->actingAs($user)
            ->get(route('homes.index'))
            ->assertOk();
    }

    public function test_create_store_update_destroy(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('homes.create'))
            ->assertOk();

        $response = $this->post(route('homes.store'), [
            'name' => 'My Home',
            'description' => 'Test home',
        ]);

        $home = Home::firstWhere('user_id', $user->id);
        $response->assertRedirect(route('homes.index'));
        $this->assertSame('My Home', $home->name);

        $this->actingAs($user)
            ->get(route('homes.edit', $home))
            ->assertOk();

        $this->actingAs($user)
            ->patch(route('homes.update', $home), [
                'name' => 'Updated Home',
                'description' => 'Updated description',
            ])->assertRedirect(route('homes.index'));

        $this->assertSame('Updated Home', $home->fresh()->name);

        $this->actingAs($user)
            ->delete(route('homes.destroy', $home))
            ->assertRedirect(route('homes.index'));

        $this->assertNull($home->fresh());
    }
}
