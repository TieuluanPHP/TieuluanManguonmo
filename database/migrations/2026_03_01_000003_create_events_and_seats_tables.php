<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('artist_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('poster_image')->nullable();
            $table->dateTime('event_date');
            $table->enum('event_type', ['standing', 'seated'])->default('standing');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'ended'])->default('pending');
            $table->text('admin_rejection_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('zone_code')->nullable();
            $table->decimal('price', 15, 2);
            $table->unsignedInteger('total_quantity');
            $table->unsignedInteger('remaining_quantity');
            $table->timestamps();
        });

        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ticket_type_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('row_number');
            $table->unsignedInteger('seat_number');
            $table->string('seat_code');
            $table->enum('status', ['available', 'booked'])->default('available');
            $table->timestamps();

            $table->index(['event_id', 'ticket_type_id', 'status']);
            $table->index(['event_id', 'row_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
        Schema::dropIfExists('ticket_types');
        Schema::dropIfExists('events');
    }
};
