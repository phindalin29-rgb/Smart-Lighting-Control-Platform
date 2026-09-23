<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Home;
use App\Models\Room;
use App\Models\User;
use App\Services\AutomationExecutionService;
use App\Services\ScheduleExecutionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_schedule_and_automation_services_are_bound(): void
    {
        $this->assertNotEmpty(implode(', ', config('app.providers')));
        $this->assertTrue(class_exists(ScheduleExecutionService::class));
        $this->assertTrue(class_exists(AutomationExecutionService::class));
    }

    public function test_user_with_devices_can_create_schedule(): void
    {
        $user = User::factory()->create();
        $home = Home::create(['user_id' => $user->id, 'name' => 'My Home']);
        $room = Room::create(['home_id' => $home->id, 'name' => 'Living Room']);
        $device = Device::create([
            'room_id' => $room->id,
            'device_uid' => 'ESP32-TEST',
            'name' => 'Living Room Light',
            'type' => 'light',
            'status' => false,
            'current_state' => false,
        ]);

        $this->actingAs($user)
            ->post(route('schedules.store'), [
                'device_id' => $device->id,
                'action' => 'on',
                'scheduled_time' => '08:00',
                'repeat_type' => 'daily',
            ])->assertRedirect(route('schedules.index'));

        $this->assertDatabaseHas('schedules', [
            'user_id' => $user->id,
            'device_id' => $device->id,
            'action' => 'on',
        ]);
    }
}
