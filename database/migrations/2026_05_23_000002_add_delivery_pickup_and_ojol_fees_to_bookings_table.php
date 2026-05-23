<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('delivery_type')->default('none')->after('return_location');
            $table->string('pickup_type')->default('none')->after('delivery_type');
            $table->decimal('delivery_fee', 15, 2)->default(0)->after('price');
            $table->decimal('pickup_fee', 15, 2)->default(0)->after('delivery_fee');
            $table->decimal('ojol_fee', 15, 2)->default(0)->after('pickup_fee');
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['delivery_type', 'pickup_type', 'delivery_fee', 'pickup_fee', 'ojol_fee']);
        });
    }
};
