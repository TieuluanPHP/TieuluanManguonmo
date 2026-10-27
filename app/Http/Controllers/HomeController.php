<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Category;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::all();
        $venues = Venue::all();
        $artists = Artist::all();

        $query = Event::with(['category', 'venue', 'artist', 'ticketTypes'])
            ->where('status', 'approved')
            ->where('event_date', '>', now())
            ->orderBy('event_date', 'asc');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('venue')) {
            $query->where('venue_id', $request->venue);
        }

        if ($request->filled('artist')) {
            $query->where('artist_id', $request->artist);
        }

        if ($request->filled('search')) {
            $keyword = '%'.$request->search.'%';
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', $keyword)
                    ->orWhere('description', 'like', $keyword);
            });
        }

        $events = $query->paginate(9)->withQueryString();

        // Featured event (first one or specific)
        $featuredEvent = Event::where('status', 'approved')
            ->where('event_date', '>=', now())
            ->latest('event_date')
            ->first();

        return view('home', compact('events', 'categories', 'venues', 'artists', 'featuredEvent'));
    }
}
