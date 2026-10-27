<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\Category;
use App\Models\Event;
use App\Models\OrganizerProfile;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ConcertBookingPlatformTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $organizer;

    private User $customer;

    private Event $seatedEvent;

    private TicketType $vipType;

    private Seat $seat1;

    private Seat $seat2;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Admin
        $this->admin = User::create([
            'name' => 'admin',
            'email' => 'admin@megatix.local',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'balance' => 0,
        ]);

        // 2. Organizer
        $this->organizer = User::create([
            'name' => 'organizer1',
            'email' => 'organizer@megatix.local',
            'password' => Hash::make('password123'),
            'role' => 'organizer',
            'balance' => 0,
        ]);

        OrganizerProfile::create([
            'user_id' => $this->organizer->id,
            'company_name' => 'Mega Music JSC',
            'phone' => '0901234567',
            'status' => 'approved',
        ]);

        // 3. Customer
        $this->customer = User::create([
            'name' => 'customer1',
            'email' => 'customer@megatix.local',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'balance' => 3000000, // 3 million
        ]);

        // 4. Taxonomies
        $cat = Category::create(['name' => 'V-Pop', 'slug' => 'v-pop']);
        $venue = Venue::create(['name' => 'SVĐ Mỹ Đình', 'address' => 'Hà Nội', 'city' => 'Hà Nội']);
        $artist = Artist::create(['name' => 'SOOBIN']);

        // 5. Seated Event
        $this->seatedEvent = Event::create([
            'organizer_id' => $this->organizer->id,
            'category_id' => $cat->id,
            'venue_id' => $venue->id,
            'artist_id' => $artist->id,
            'title' => 'Anh Trai Say Hi Live 2026',
            'slug' => 'anh-trai-say-hi-live-2026',
            'description' => 'Mô tả concert',
            'poster_image' => 'https://example.com/poster.jpg',
            'event_date' => now()->addDays(10),
            'event_type' => 'seated',
            'status' => 'approved',
        ]);

        $this->vipType = TicketType::create([
            'event_id' => $this->seatedEvent->id,
            'name' => 'Khu VIP A',
            'zone_code' => 'A',
            'price' => 1000000,
            'total_quantity' => 10,
            'remaining_quantity' => 10,
        ]);

        $this->seat1 = Seat::create([
            'event_id' => $this->seatedEvent->id,
            'ticket_type_id' => $this->vipType->id,
            'row_number' => 1,
            'seat_number' => 1,
            'seat_code' => 'A-1-01',
            'status' => 'available',
        ]);

        $this->seat2 = Seat::create([
            'event_id' => $this->seatedEvent->id,
            'ticket_type_id' => $this->vipType->id,
            'row_number' => 1,
            'seat_number' => 2,
            'seat_code' => 'A-1-02',
            'status' => 'available',
        ]);
    }

    public function test_home_page_loads_and_displays_events(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Anh Trai Say Hi Live 2026');
    }

    public function test_auth_login_with_username_and_password(): void
    {
        $response = $this->post('/login', [
            'name' => 'customer1',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($this->customer);
    }

    public function test_customer_can_deposit_money_into_internal_wallet(): void
    {
        $this->actingAs($this->customer);

        $response = $this->post('/wallet/deposit', [
            'amount' => 500000,
        ]);

        $response->assertRedirect(route('wallet.index'));
        $this->customer->refresh();
        $this->assertEquals(3500000, (float) $this->customer->balance);
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $this->customer->id,
            'amount' => 500000,
            'type' => 'deposit',
        ]);
    }

    public function test_customer_can_successfully_book_seated_ticket(): void
    {
        $this->actingAs($this->customer);

        $response = $this->post('/booking/checkout', [
            'event_id' => $this->seatedEvent->id,
            'ticket_type_id' => $this->vipType->id,
            'seat_ids' => [$this->seat1->id, $this->seat2->id],
        ]);

        $response->assertRedirect(route('tickets.my'));

        // Balance deducted by 2 * 1,000,000 = 2,000,000 (3,000,000 - 2,000,000 = 1,000,000)
        $this->customer->refresh();
        $this->assertEquals(1000000, (float) $this->customer->balance);

        // Seats marked as booked
        $this->seat1->refresh();
        $this->seat2->refresh();
        $this->assertEquals('booked', $this->seat1->status);
        $this->assertEquals('booked', $this->seat2->status);

        // Tickets created with 8-character format
        $tickets = Ticket::where('user_id', $this->customer->id)->get();
        $this->assertCount(2, $tickets);
        foreach ($tickets as $t) {
            $this->assertMatchesRegularExpression('/^[A-Z]{4}-\d{4}$/', $t->ticket_code);
            $this->assertEquals('valid', $t->status);
        }
    }

    public function test_double_booking_same_seat_is_prevented(): void
    {
        // Mark seat 1 as booked
        $this->seat1->update(['status' => 'booked']);

        $this->actingAs($this->customer);

        $response = $this->post('/booking/checkout', [
            'event_id' => $this->seatedEvent->id,
            'ticket_type_id' => $this->vipType->id,
            'seat_ids' => [$this->seat1->id],
        ]);

        $response->assertSessionHas('error');
        // Balance remains unchanged
        $this->customer->refresh();
        $this->assertEquals(3000000, (float) $this->customer->balance);
    }

    public function test_insufficient_wallet_balance_blocks_purchase(): void
    {
        // Set customer balance to 500,000 (ticket is 1,000,000)
        $this->customer->update(['balance' => 500000]);

        $this->actingAs($this->customer);

        $response = $this->post('/booking/checkout', [
            'event_id' => $this->seatedEvent->id,
            'ticket_type_id' => $this->vipType->id,
            'seat_ids' => [$this->seat1->id],
        ]);

        $response->assertSessionHas('error');
        $this->seat1->refresh();
        $this->assertEquals('available', $this->seat1->status);
    }

    public function test_ajax_endpoints_return_correct_dropdown_data(): void
    {
        $response = $this->getJson("/api/ticket-types/{$this->vipType->id}/available-rows");
        $response->assertStatus(200);
        $response->assertJson([1]);

        $seatsResponse = $this->getJson("/api/ticket-types/{$this->vipType->id}/rows/1/available-seats");
        $seatsResponse->assertStatus(200);
        $seatsResponse->assertJsonCount(2);
    }

    public function test_admin_can_cancel_concert_and_auto_refund_customers(): void
    {
        // 1. Customer buys 1 ticket
        $this->actingAs($this->customer);
        $this->post('/booking/checkout', [
            'event_id' => $this->seatedEvent->id,
            'ticket_type_id' => $this->vipType->id,
            'seat_ids' => [$this->seat1->id],
        ]);
        $this->customer->refresh();
        $this->assertEquals(2000000, (float) $this->customer->balance);

        // 2. Admin cancels the event
        $this->actingAs($this->admin);
        $cancelResponse = $this->post("/admin/events/{$this->seatedEvent->id}/cancel");
        $cancelResponse->assertSessionHas('success');

        // 3. Verify event is cancelled
        $this->seatedEvent->refresh();
        $this->assertEquals('cancelled', $this->seatedEvent->status);

        // 4. Verify 100% refund credited back to customer wallet
        $this->customer->refresh();
        $this->assertEquals(3000000, (float) $this->customer->balance);

        // 5. Verify ticket status is refunded
        $ticket = Ticket::where('seat_id', $this->seat1->id)->first();
        $this->assertEquals('refunded', $ticket->status);

        // 6. Verify seat is released
        $this->seat1->refresh();
        $this->assertEquals('available', $this->seat1->status);
    }

    public function test_cannot_book_tickets_for_past_event(): void
    {
        // Set event date to yesterday
        $this->seatedEvent->update(['event_date' => now()->subDay()]);

        $this->actingAs($this->customer);

        $response = $this->post('/booking/checkout', [
            'event_id' => $this->seatedEvent->id,
            'ticket_type_id' => $this->vipType->id,
            'seat_ids' => [$this->seat1->id],
        ]);

        $response->assertSessionHas('error', 'Sự kiện này đã kết thúc hoặc không còn mở bán vé.');

        // Balance untouched
        $this->customer->refresh();
        $this->assertEquals(3000000, (float) $this->customer->balance);

        // Seat remains available
        $this->seat1->refresh();
        $this->assertEquals('available', $this->seat1->status);
    }

    public function test_past_event_ajax_endpoints_return_forbidden(): void
    {
        $this->seatedEvent->update(['event_date' => now()->subDay()]);

        $response = $this->getJson("/api/events/{$this->seatedEvent->id}/ticket-types");
        $response->assertStatus(403);

        $rowsResponse = $this->getJson("/api/ticket-types/{$this->vipType->id}/available-rows");
        $rowsResponse->assertStatus(403);
    }

    public function test_past_event_displays_closed_notice_on_page(): void
    {
        $this->seatedEvent->update(['event_date' => now()->subDay()]);

        $response = $this->get("/events/{$this->seatedEvent->slug}");
        $response->assertStatus(200);
        $response->assertSee('Sự Kiện Đã Diễn Ra (Đóng Cổng)');
    }

    public function test_booking_closes_24_hours_before_event_starts(): void
    {
        // Event is scheduled 12 hours from now (within 24h gate closing window)
        $this->seatedEvent->update(['event_date' => now()->addHours(12)]);

        $this->assertTrue($this->seatedEvent->isBookingClosed());
        $this->assertFalse($this->seatedEvent->canBeBooked());

        $this->actingAs($this->customer);

        $response = $this->post('/booking/checkout', [
            'event_id' => $this->seatedEvent->id,
            'ticket_type_id' => $this->vipType->id,
            'seat_ids' => [$this->seat1->id],
        ]);

        $response->assertSessionHas('error', 'Sự kiện này đã kết thúc hoặc không còn mở bán vé.');
    }

    public function test_past_events_are_automatically_hidden_from_homepage(): void
    {
        // Event date is past
        $this->seatedEvent->update(['event_date' => now()->subHours(5)]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee($this->seatedEvent->title);
    }
}
