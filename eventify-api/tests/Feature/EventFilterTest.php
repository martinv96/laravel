<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EventFilterTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_returns_paginated_results()
    {
        $user = User::factory()->create();

        // Création de 15 événements pour avoir plus de 10 résultats
        Event::factory()->count(15)->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/events?per_page=10');

        $response->assertOk()
            ->assertJsonStructure([
                'current_page',
                'data',
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ])
            ->assertJsonCount(10, 'data');
    }


    public function test_it_filters_by_category()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);   // authentification

        $cat1 = Category::factory()->create();
        $cat2 = Category::factory()->create();

        Event::factory()->count(5)->create([
            'category_id' => $cat1->id,
            'user_id' => $user->id,
        ]);
        Event::factory()->count(3)->create([
            'category_id' => $cat2->id,
            'user_id' => $user->id,
        ]);

        $response = $this->getJson("/api/events?category_id={$cat2->id}");

        $response->assertOk()->assertJsonCount(3, 'data');
    }

    public function test_it_filters_by_search_keyword()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);   // authentification

        Event::factory()->create([
            'title' => 'Laravel Conference',
            'user_id' => $user->id,
        ]);
        Event::factory()->create([
            'title' => 'VueJS Workshop',
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/events?search=Laravel');

        $response->assertOk()
            ->assertJsonFragment(['title' => 'Laravel Conference']);
    }
}
