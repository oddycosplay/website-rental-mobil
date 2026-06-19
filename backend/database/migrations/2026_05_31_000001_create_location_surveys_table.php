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
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('surveyor_name');
            $table->date('survey_date');
            $table->enum('survey_type', ['delivery', 'pickup'])->default('delivery');
            $table->text('address');
            $table->json('residence_status')->nullable();
            $table->json('job_status')->nullable();
            $table->json('neighbor_interview')->nullable();
            $table->json('photos')->nullable();
            $table->enum('recommendation', ['layak', 'tidak_layak'])->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
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
