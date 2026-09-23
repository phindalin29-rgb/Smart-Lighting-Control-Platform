<?php

namespace Tests\Feature\Automation;

use App\Models\AutomationRule;
use App\Models\Device;
use App\Models\Home;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutomationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_are_redirected(): void
    {
        $this->get(route('automation.index'))
            ->assertRedirect(route('login'));
    }

    public function test_index_displays_rules(): void
    {
        $user = User::factory()->create();
        $device = $this->createDeviceForUser($user);
        AutomationRule::create([
            'user_id' => $user->id,
            'device_id' => $device->id,
            'name' => 'Evening Mode',
            'condition_type' => 'time',
            'condition_value' => '18:00',
            'action' => 'on',
        ]);

        $this->actingAs($user)
            ->get(route('automation.index'))
            ->assertOk();
    }

    public function test_create_store_update_destroy(): void
    {
        $user = User::factory()->create();
        $device = $this->createDeviceForUser($user);

        $this->actingAs($user)
            ->get(route('automation.create'))
            ->assertOk();

        $response = $this->post(route('automation.store'), [
            'name' => 'Evening Mode',
            'device_id' => $device->id,
            'condition_type' => 'time',
            'condition_value' => '18:00',
            'action' => 'on',
        ]);

        $rule = AutomationRule::firstWhere('user_id', $user->id);
        $response->assertRedirect(route('automation.index'));
        $this->assertSame('Evening Mode', $rule->name);

        $this->actingAs($user)
            ->get(route('automation.edit', $rule))
            ->assertOk();

        $this->actingAs($user)
            ->patch(route('automation.update', $rule), [
                'name' => 'Morning Mode',
                'device_id' => $device->id,
                'condition_type' => 'time',
                'condition_value' => '06:00',
                'action' => 'off',
            ])->assertRedirect(route('automation.index'));

        $this->assertSame('Morning Mode', $rule->fresh()->name);

        $this->actingAs($user)
            ->delete(route('automation.destroy', $rule))
            ->assertRedirect(route('automation.index'));

        $this->assertNull($rule->fresh());
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
