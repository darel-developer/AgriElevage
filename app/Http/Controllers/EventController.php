<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);
        $event = Event::create($request->only('title', 'description', 'date'));
        return response()->json(['success' => true, 'event' => $event]);
    }

    public function events()
    {
        return response()->json(Event::all());
    }

    public function details($date)
    {
        $events = Event::where('date', $date)->get();
        return response()->json($events);
    }
}
