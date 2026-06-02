<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('location_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('surveyor_name');
            $table->date('survey_date');
            $table->enum('survey_type', ['delivery', 'pickup'])->default('delivery');
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('distance_km', 6, 2)->nullable();

            // JSON Checklists
            $table->json('road_condition')->nullable();   // kondisi jalan
            $table->json('access_condition')->nullable(); // akses kendaraan
            $table->json('area_safety')->nullable();      // keamanan area
            $table->json('photos')->nullable();           // foto lokasi

            $table->enum('recommendation', ['layak', 'tidak_layak', 'layak_dengan_catatan'])->nullable();
            $table->text('notes')->nullable();

            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_surveys');
    }
};
