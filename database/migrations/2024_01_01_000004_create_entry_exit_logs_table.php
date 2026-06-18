<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entry_exit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('spot_id')->nullable()->constrained('parking_spots')->onDelete('set null');
            $table->foreignId('reservation_id')->nullable()->constrained('reservations')->onDelete('set null');
            $table->enum('event_type', ['entry', 'exit']);
            $table->string('vehicle_plate')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->timestamp('event_time');
            $table->text('notes')->nullable();
            $table->string('recorded_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_exit_logs');
    }
};
