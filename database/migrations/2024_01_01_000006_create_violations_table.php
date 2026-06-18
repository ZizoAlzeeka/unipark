<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('spot_id')->nullable()->constrained('parking_spots')->onDelete('set null');
            $table->foreignId('reservation_id')->nullable()->constrained('reservations')->onDelete('set null');
            $table->enum('type', ['unauthorized', 'overstay', 'wrong_spot', 'no_reservation', 'accessible_misuse'])->default('unauthorized');
            $table->text('description')->nullable();
            $table->string('vehicle_plate')->nullable();
            $table->enum('status', ['pending', 'resolved', 'appealed', 'dismissed'])->default('pending');
            $table->decimal('fine_amount', 8, 2)->default(0);
            $table->boolean('fine_paid')->default(false);
            $table->timestamp('violation_time')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
