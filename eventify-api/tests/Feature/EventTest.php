<?php 

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_event()
    {
        Sanctum::actingAs(User::factory()->create());
        $category = Category::factory()->create();

        $response = $this->postJson('/api/events', [
            'title' => 'Test Event',
            'description' => 'Description',
            'date' => now()->addDays(1)->format('Y-m-d'),
            'time' => '14:00',
            'location' => 'Paris',
            'category_id' => $category->id,
        ]);

        $response->assertCreated()
                 ->assertJsonFragment(['title' => 'Test Event']);
    }
}
