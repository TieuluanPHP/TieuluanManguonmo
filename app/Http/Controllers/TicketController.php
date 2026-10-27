<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function myTickets(): View
    {
        $user = Auth::user();

        $tickets = Ticket::with(['order.event.venue', 'ticketType', 'seat'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('tickets.index', compact('tickets'));
    }
}
