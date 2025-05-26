<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reserve_an_event()
    {
        Sanctum::actingAs($user = User::factory()->create());
        // Créer un autre utilisateur qui sera propriétaire de l'event
        $eventOwner = User::factory()->create();

        $event = Event::factory()->create([
            'user_id' => $eventOwner->id,
        ]);

        $response = $this->postJson('/api/reservations', [
            'event_id' => $event->id,
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }
}
