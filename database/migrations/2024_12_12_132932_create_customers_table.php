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
        // khách hàng
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('id_card_number')->nullable();
            $table->string('driving_license_number')->nullable();
            $table->string('address')->nullable()            ;
            $table->string('phone_number')->nullable();
            $table->string('password');
            $table->enum('status', ['active', 'temporary_lock', 'permanently_locked'])->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
