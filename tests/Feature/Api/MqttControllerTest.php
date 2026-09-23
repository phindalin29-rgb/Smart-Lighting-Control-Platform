<?php

namespace Tests\Feature\Api;

use App\Models\Device;
use App\Models\Home;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MqttControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_updates_device(): void
    {
        $device = $this->createDevice();

        $response = $this->postJson('/api/mqtt/status', [
            'device_uid' => $device->device_uid,
            'state' => true,
            'online' => true,
        ]);

        $response->assertOk();
        $this->assertTrue($device->fresh()->status);
        $this->assertTrue($device->fresh()->current_state);
    }

    public function test_command_returns_accepted(): void
    {
        $device = $this->createDevice();

        $response = $this->postJson('/api/mqtt/command', [
            'device_uid' => $device->device_uid,
            'action' => 'on',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['message', 'published', 'device_id']);
    }

    public function test_command_requires_valid_device(): void
    {
        $response = $this->postJson('/api/mqtt/command', [
            'device_uid' => 'MISSING',
            'action' => 'on',
        ]);

        $response->assertStatus(404);
    }

    private function createDevice(): Device
    {
        $home = Home::create(['user_id' => User::factory()->create()->id, 'name' => 'My Home']);
        $room = Room::create(['home_id' => $home->id, 'name' => 'Bedroom']);

        return Device::create([
            'room_id' => $room->id,
            'device_uid' => 'ESP32-TEST',
            'name' => 'Test Device',
            'type' => 'light',
            'status' => false,
            'current_state' => false,
        ]);
    }
}
