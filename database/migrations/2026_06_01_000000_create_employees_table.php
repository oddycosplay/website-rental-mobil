<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('nip', 50)->nullable()->unique();
            $table->string('position', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pindahkan data user non-customer yang sudah ada ke tabel employees
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            // Cek apakah user ini adalah customer
            $hasCustomerRole = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_has_roles.model_type', 'App\\Models\\User')
                ->where('roles.name', 'customer')
                ->exists();

            if (!$hasCustomerRole) {
                // Cari role non-customer untuk dijadikan position
                $role = DB::table('model_has_roles')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->where('model_has_roles.model_id', $user->id)
                    ->where('model_has_roles.model_type', 'App\\Models\\User')
                    ->value('roles.name');

                $position = $role ? ucwords(str_replace('-', ' ', $role)) : 'Staff';
                $nip = 'NIP-' . str_pad($user->id, 5, '0', STR_PAD_LEFT);

                DB::table('employees')->insert([
                    'user_id' => $user->id,
                    'store_id' => $user->store_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'nip' => $nip,
                    'position' => $position,
                    'is_active' => $user->is_active ?? true,
                    'created_at' => $user->created_at ?? now(),
                    'updated_at' => $user->updated_at ?? now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
