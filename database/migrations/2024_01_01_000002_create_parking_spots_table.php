<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parking_spots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('parking_zones')->onDelete('cascade');
            $table->string('spot_number', 20);
            $table->enum('type', ['standard', 'staff', 'disabled', 'vip'])->default('standard');
            $table->enum('status', ['available', 'reserved', 'occupied', 'maintenance'])->default('available');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['zone_id', 'spot_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parking_spots');
    }
};
