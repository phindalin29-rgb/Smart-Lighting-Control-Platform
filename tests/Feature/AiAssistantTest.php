<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiAssistantTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_are_redirected(): void
    {
        $this->post(route('ai.chat'), [
            'message' => 'turn on the light',
        ])->assertRedirect(route('login'));
    }

    public function test_ai_can_detect_unsupported_intent(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('ai.chat'), [
                'message' => 'something unknown command',
            ])->assertSessionHas('ai_result');

        $this->get(route('ai.index'))
            ->assertSee('could not detect');
    }
}
