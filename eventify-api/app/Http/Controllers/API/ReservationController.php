<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Event;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the authenticated user's reservations.
     */
    public function index(Request $request)
    {
        $reservations = $request->user()
            ->reservations()
            ->with('event.category')
            ->latest()
            ->get();

        return response()->json($reservations);
    }

    /**
     * Store a new reservation for an event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        // Vérifier si l'utilisateur a déjà réservé cet événement
        $alreadyReserved = Reservation::where('user_id', $request->user()->id)
            ->where('event_id', $validated['event_id'])
            ->exists();

        if ($alreadyReserved) {
            return response()->json([
                'message' => 'Vous avez déjà réservé cet événement.',
            ], 409); // 409 Conflict
        }

        $reservation = Reservation::create([
            'user_id'  => $request->user()->id,
            'event_id' => $validated['event_id'],
        ]);

        return response()->json($reservation, 201);
    }

    /**
     * Display the specified reservation (if it belongs to the user).
     */
    public function show(string $id, Request $request)
    {
        $reservation = Reservation::with('event.category')
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return response()->json($reservation);
    }

    /**
     * Update is not typically used for reservations, but can allow changes.
     */
    public function update(Request $request, string $id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $reservation->update($validated);

        return response()->json($reservation);
    }

    /**
     * Delete the reservation.
     */
    public function destroy(string $id, Request $request)
    {
        $reservation = Reservation::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $reservation->delete();

        return response()->json(null, 204);
    }
}
