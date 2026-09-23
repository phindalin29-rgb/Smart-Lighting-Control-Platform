<?php

namespace Tests\Feature\Schedule;

use App\Models\Device;
use App\Models\Home;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_are_redirected(): void
    {
        $this->get(route('schedules.index'))
            ->assertRedirect(route('login'));
    }

    public function test_index_displays_schedules(): void
    {
        $user = User::factory()->create();
        $device = $this->createDeviceForUser($user);
        Schedule::create([
            'user_id' => $user->id,
            'device_id' => $device->id,
            'action' => 'on',
            'scheduled_time' => '22:00',
            'repeat_type' => 'daily',
        ]);

        $this->actingAs($user)
            ->get(route('schedules.index'))
            ->assertOk();
    }

    public function test_create_store_update_destroy(): void
    {
        $user = User::factory()->create();
        $device = $this->createDeviceForUser($user);

        $this->actingAs($user)
            ->get(route('schedules.create'))
            ->assertOk();

        $response = $this->post(route('schedules.store'), [
            'device_id' => $device->id,
            'action' => 'on',
            'scheduled_time' => '22:00',
            'repeat_type' => 'daily',
        ]);

        $schedule = Schedule::firstWhere('user_id', $user->id);
        $response->assertRedirect(route('schedules.index'));
        $this->assertSame('22:00', $schedule->scheduled_time?->format('H:i'));

        $this->actingAs($user)
            ->get(route('schedules.edit', $schedule))
            ->assertOk();

        $this->actingAs($user)
            ->patch(route('schedules.update', $schedule), [
                'device_id' => $device->id,
                'action' => 'off',
                'scheduled_time' => '06:00',
                'repeat_type' => 'weekly',
            ])->assertRedirect(route('schedules.index'));

        $this->assertSame('06:00', $schedule->fresh()->scheduled_time?->format('H:i'));

        $this->actingAs($user)
            ->delete(route('schedules.destroy', $schedule))
            ->assertRedirect(route('schedules.index'));

        $this->assertNull($schedule->fresh());
    }

    private function createDeviceForUser(User $user): Device
    {
        $home = Home::create(['user_id' => $user->id, 'name' => 'My Home']);
        $room = Room::create(['home_id' => $home->id, 'name' => 'Bedroom']);

        return Device::create([
            'room_id' => $room->id,
            'device_uid' => 'ESP32-'.$user->id.'-'.$room->id,
            'name' => 'Test Device',
            'type' => 'light',
            'status' => false,
            'current_state' => false,
        ]);
    }
}
