<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Seat;
use App\Models\TicketType;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EventDetailController extends Controller
{
    public function show(string $slug): View
    {
        $event = Event::with(['category', 'venue', 'artist', 'organizer.organizerProfile', 'ticketTypes.seats'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Calculate min price
        $minPrice = $event->ticketTypes->min('price');

        return view('events.show', compact('event', 'minPrice'));
    }

    /**
     * AJAX: Get ticket types for event
     */
    public function apiTicketTypes(int $eventId): JsonResponse
    {
        $event = Event::find($eventId);
        if (! $event || ! $event->canBeBooked()) {
            return response()->json(['message' => 'Sự kiện này đã kết thúc hoặc không mở bán.'], 403);
        }

        $ticketTypes = TicketType::where('event_id', $eventId)
            ->withCount(['seats as available_seats_count' => function ($query) {
                $query->where('status', 'available');
            }])
            ->get();

        return response()->json($ticketTypes);
    }

    /**
     * AJAX: Get distinct available rows for a ticket type
     */
    public function apiAvailableRows(int $ticketTypeId): JsonResponse
    {
        $ticketType = TicketType::with('event')->find($ticketTypeId);
        if (! $ticketType || ! $ticketType->event || ! $ticketType->event->canBeBooked()) {
            return response()->json(['message' => 'Sự kiện này đã kết thúc hoặc không mở bán.'], 403);
        }

        $rows = Seat::where('ticket_type_id', $ticketTypeId)
            ->where('status', 'available')
            ->select('row_number')
            ->distinct()
            ->orderBy('row_number', 'asc')
            ->pluck('row_number');

        return response()->json($rows);
    }

    /**
     * AJAX: Get available seats in a specific row
     */
    public function apiAvailableSeats(int $ticketTypeId, int $rowNumber): JsonResponse
    {
        $ticketType = TicketType::with('event')->find($ticketTypeId);
        if (! $ticketType || ! $ticketType->event || ! $ticketType->event->canBeBooked()) {
            return response()->json(['message' => 'Sự kiện này đã kết thúc hoặc không mở bán.'], 403);
        }

        $seats = Seat::where('ticket_type_id', $ticketTypeId)
            ->where('row_number', $rowNumber)
            ->where('status', 'available')
            ->orderBy('seat_number', 'asc')
            ->get(['id', 'seat_number', 'seat_code', 'status']);

        return response()->json($seats);
    }
}
