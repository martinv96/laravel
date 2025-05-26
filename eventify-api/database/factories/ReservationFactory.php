<?php 

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
