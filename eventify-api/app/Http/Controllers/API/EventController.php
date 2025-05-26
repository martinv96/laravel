<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the resource with pagination, filters and search.
     */
    public function index(Request $request)
    {
        // Validation simple des filtres
        $request->validate([
            'category_id' => 'nullable|integer|exists:categories,id',
            'date'        => 'nullable|date',
            'search'      => 'nullable|string|max:255',
            'per_page'    => 'nullable|integer|min:1|max:100',
            'page'        => 'nullable|integer|min:1',
        ]);

        $query = Event::query();

        // Filtre par catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filtre par date (sur datetime)
        if ($request->filled('date')) {
            $query->whereDate('datetime', $request->date);
        }

        // Recherche sur titre ou description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->input('per_page', 10);

        $events = $query->with('category')
                        ->orderBy('datetime', 'asc')
                        ->paginate($perPage);

        return response()->json($events);
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'date'         => 'required|date',
            'time'         => 'required|date_format:H:i',
            'location'     => 'required|string|max:255',
            'category_id'  => 'required|integer|exists:categories,id',
        ]);

        // Fusion date + time -> datetime
        $validated['datetime'] = Carbon::parse("{$validated['date']} {$validated['time']}");
        unset($validated['date'], $validated['time']);

        // Lier l'utilisateur connecté (optionnel, mais recommandé)
        $validated['user_id'] = $request->user()->id;

        $event = Event::create($validated);

        return response()->json($event, 201);
    }

    /**
     * Display the specified event.
     */
    public function show(string $id)
    {
        $event = Event::with('category')->findOrFail($id);

        return response()->json($event);
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title'        => 'sometimes|required|string|max:255',
            'description'  => 'sometimes|required|string',
            'date'         => 'sometimes|required|date',
            'time'         => 'sometimes|required|date_format:H:i',
            'location'     => 'sometimes|required|string|max:255',
            'category_id'  => 'sometimes|required|integer|exists:categories,id',
        ]);

        // Fusion date + time si présents
        if (isset($validated['date']) && isset($validated['time'])) {
            $validated['datetime'] = Carbon::parse("{$validated['date']} {$validated['time']}");
            unset($validated['date'], $validated['time']);
        }

        $event->update($validated);

        return response()->json($event);
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(null, 204);
    }
}
