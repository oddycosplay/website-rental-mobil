<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operationals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->string('inspector_name');
            $table->enum('inspection_type', ['pre_rental', 'post_rental']);
            $table->timestamp('inspected_at');
            $table->integer('odometer_km')->nullable();
            $table->enum('fuel_level', ['full', 'three_quarter', 'half', 'quarter', 'empty'])->default('full');

            // JSON kondisi detail
            $table->json('exterior')->nullable();    // bodi, kaca, spion, lampu, ban
            $table->json('interior')->nullable();    // kabin, jok, AC, audio, dll
            $table->json('equipment')->nullable();   // STNK, dongkrak, e-toll, dll
            $table->json('engine')->nullable();      // mesin, oli, radiator, BBM
            $table->json('photos')->nullable();      // foto dokumentasi

            // Kerusakan (post_rental)
            $table->boolean('damage_found')->default(false);
            $table->text('damage_description')->nullable();
            $table->decimal('damage_cost', 12, 2)->nullable();
            $table->json('damage_photos')->nullable();

            // Konfirmasi pelanggan
            $table->boolean('customer_confirmed')->default(false);
            $table->text('customer_note')->nullable();
            $table->text('notes')->nullable();

            $table->enum('status', ['draft', 'submitted', 'approved'])->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operationals');
    }
};
