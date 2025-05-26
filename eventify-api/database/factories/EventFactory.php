<?php

namespace Database\Factories;


use App\Models\Event;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
{
    return [
        'user_id' => User::factory(), // crée automatiquement un user valide
        'category_id' => Category::factory(),
        'title' => $this->faker->sentence(),
        'description' => $this->faker->paragraph(),
        'datetime' => $this->faker->dateTimeBetween('+1 days', '+1 month'),
        'location' => $this->faker->city(),
    ];
}
}

