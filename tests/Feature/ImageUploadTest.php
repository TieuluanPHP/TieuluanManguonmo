<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\Category;
use App\Models\Event;
use App\Models\OrganizerProfile;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageUploadTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $organizer;

    private Category $category;

    private Venue $venue;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->admin = User::create([
            'name' => 'admin',
            'email' => 'admin@megatix.local',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'balance' => 0,
        ]);

        $this->organizer = User::create([
            'name' => 'organizer1',
            'email' => 'organizer@megatix.local',
            'password' => Hash::make('password123'),
            'role' => 'organizer',
            'balance' => 0,
        ]);

        OrganizerProfile::create([
            'user_id' => $this->organizer->id,
            'company_name' => 'Mega Stage Entertainment',
            'phone' => '0912345678',
            'status' => 'approved',
        ]);

        $this->category = Category::create(['name' => 'Rock', 'slug' => 'rock']);
        $this->venue = Venue::create(['name' => 'SVĐ Mỹ Đình', 'address' => 'Hà Nội', 'city' => 'Hà Nội']);
    }

    public function test_organizer_can_create_event_with_uploaded_poster_file(): void
    {
        $this->actingAs($this->organizer);

        $fakeFile = UploadedFile::fake()->image('test_poster.jpg', 1200, 800)->size(1500); // 1.5MB

        $response = $this->post(route('organizer.events.store'), [
            'title' => 'Rock Festival 2026',
            'category_id' => $this->category->id,
            'venue_id' => $this->venue->id,
            'event_date' => now()->addDays(20)->format('Y-m-d H:i:s'),
            'event_type' => 'standing',
            'description' => 'Mô tả đại nhạc hội rock đỉnh cao.',
            'poster_file' => $fakeFile,
            'standing_tickets' => [
                ['name' => 'Vé GA', 'price' => 500000, 'quantity' => 100],
            ],
        ]);

        $response->assertRedirect(route('organizer.dashboard'));
        $this->assertDatabaseHas('events', [
            'title' => 'Rock Festival 2026',
        ]);

        $event = Event::where('title', 'Rock Festival 2026')->first();
        $this->assertNotNull($event->poster_image);
        Storage::disk('public')->assertExists($event->poster_image);
    }

    public function test_organizer_can_update_event_with_new_uploaded_poster(): void
    {
        $this->actingAs($this->organizer);

        $event = Event::create([
            'organizer_id' => $this->organizer->id,
            'category_id' => $this->category->id,
            'venue_id' => $this->venue->id,
            'title' => 'Concert Cuối Năm',
            'slug' => 'concert-cuoi-nam',
            'description' => 'Mô tả cũ',
            'poster_image' => 'https://example.com/old_poster.jpg',
            'event_date' => now()->addDays(15),
            'event_type' => 'standing',
            'status' => 'pending',
        ]);

        $newFile = UploadedFile::fake()->image('updated_poster.png', 800, 600);

        $response = $this->put(route('organizer.events.update', $event->id), [
            'title' => 'Concert Cuối Năm - Updated',
            'description' => 'Mô tả mới sau cập nhật',
            'poster_file' => $newFile,
        ]);

        $response->assertRedirect(route('organizer.dashboard'));
        $event->refresh();
        $this->assertEquals('Concert Cuối Năm - Updated', $event->title);
        $this->assertNotEquals('https://example.com/old_poster.jpg', $event->poster_image);
        Storage::disk('public')->assertExists($event->poster_image);
    }

    public function test_admin_can_create_artist_with_uploaded_image_file(): void
    {
        $this->actingAs($this->admin);

        $artistFile = UploadedFile::fake()->image('singer.jpg', 500, 500)->size(800);

        $response = $this->post(route('admin.taxonomies.artist.store'), [
            'name' => 'Mỹ Tâm',
            'bio' => 'Họa mi tóc nâu của làng nhạc Việt',
            'image_file' => $artistFile,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('artists', [
            'name' => 'Mỹ Tâm',
        ]);

        $artist = Artist::where('name', 'Mỹ Tâm')->first();
        $this->assertNotNull($artist->image);
        Storage::disk('public')->assertExists($artist->image);
    }

    public function test_validation_rejects_non_image_files(): void
    {
        $this->actingAs($this->organizer);

        $fakeTextFile = UploadedFile::fake()->create('malicious.txt', 100, 'text/plain');

        $response = $this->post(route('organizer.events.store'), [
            'title' => 'Rock Festival Test',
            'category_id' => $this->category->id,
            'venue_id' => $this->venue->id,
            'event_date' => now()->addDays(20)->format('Y-m-d H:i:s'),
            'event_type' => 'standing',
            'description' => 'Mô tả',
            'poster_file' => $fakeTextFile,
        ]);

        $response->assertSessionHasErrors('poster_file');
    }

    public function test_poster_file_is_strictly_required_when_creating_event(): void
    {
        $this->actingAs($this->organizer);

        $response = $this->post(route('organizer.events.store'), [
            'title' => 'Concert Without Poster File',
            'category_id' => $this->category->id,
            'venue_id' => $this->venue->id,
            'event_date' => now()->addDays(20)->format('Y-m-d H:i:s'),
            'event_type' => 'standing',
            'description' => 'Mô tả test',
            'standing_tickets' => [
                ['name' => 'Vé GA', 'price' => 500000, 'quantity' => 100],
            ],
        ]);

        $response->assertSessionHasErrors('poster_file');
    }

    public function test_submitting_poster_image_url_is_no_longer_accepted_without_file(): void
    {
        $this->actingAs($this->organizer);

        $response = $this->post(route('organizer.events.store'), [
            'title' => 'Concert With Only URL',
            'category_id' => $this->category->id,
            'venue_id' => $this->venue->id,
            'event_date' => now()->addDays(20)->format('Y-m-d H:i:s'),
            'event_type' => 'standing',
            'description' => 'Mô tả test',
            'poster_image' => 'https://images.unsplash.com/photo-test',
            'standing_tickets' => [
                ['name' => 'Vé GA', 'price' => 500000, 'quantity' => 100],
            ],
        ]);

        // poster_file must be required regardless of poster_image URL
        $response->assertSessionHasErrors('poster_file');
    }

    public function test_sample_events_and_artists_with_external_urls_continue_rendering_correctly(): void
    {
        $sampleEvent = Event::create([
            'organizer_id' => $this->organizer->id,
            'category_id' => $this->category->id,
            'venue_id' => $this->venue->id,
            'title' => 'Sample Seeded Concert',
            'slug' => 'sample-seeded-concert',
            'description' => 'Sự kiện mẫu từ database seeder',
            'poster_image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=1200',
            'event_date' => now()->addDays(10),
            'event_type' => 'standing',
            'status' => 'approved',
        ]);

        $sampleArtist = Artist::create([
            'name' => 'Sơn Tùng M-TP',
            'bio' => 'Sample Artist Bio',
            'image' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=600',
        ]);

        // Model accessors must preserve original sample URLs
        $this->assertEquals(
            'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=1200',
            $sampleEvent->poster_url
        );
        $this->assertEquals(
            'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=600',
            $sampleArtist->image_url
        );
    }
}
