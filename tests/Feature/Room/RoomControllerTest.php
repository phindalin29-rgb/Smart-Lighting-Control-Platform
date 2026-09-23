<?php

namespace Tests\Feature\Room;

use App\Models\Home;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_are_redirected(): void
    {
        $this->get(route('rooms.index'))
            ->assertRedirect(route('login'));
    }

    public function test_index_displays_rooms(): void
    {
        $user = User::factory()->create();
        $home = Home::create(['user_id' => $user->id, 'name' => 'My Home']);
        Room::create(['home_id' => $home->id, 'name' => 'Bedroom']);

        $this->actingAs($user)
            ->get(route('rooms.index'))
            ->assertOk();
    }

    public function test_create_store_update_destroy(): void
    {
        $user = User::factory()->create();
        $home = Home::create(['user_id' => $user->id, 'name' => 'My Home']);

        $this->actingAs($user)
            ->get(route('rooms.create'))
            ->assertOk();

        $response = $this->post(route('rooms.store'), [
            'home_id' => $home->id,
            'name' => 'Living Room',
            'description' => 'Test room',
        ]);

        $room = Room::firstWhere('home_id', $home->id);
        $response->assertRedirect(route('rooms.index'));
        $this->assertSame('Living Room', $room->name);

        $this->actingAs($user)
            ->get(route('rooms.edit', $room))
            ->assertOk();

        $this->actingAs($user)
            ->patch(route('rooms.update', $room), [
                'home_id' => $home->id,
                'name' => 'Updated Room',
                'description' => 'Updated description',
            ])->assertRedirect(route('rooms.index'));

        $this->assertSame('Updated Room', $room->fresh()->name);

        $this->actingAs($user)
            ->delete(route('rooms.destroy', $room))
            ->assertRedirect(route('rooms.index'));

        $this->assertNull($room->fresh());
    }
}
