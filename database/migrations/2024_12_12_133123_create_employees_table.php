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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('id_card_number')->unique();
            $table->decimal('salary', 10, 2);
            $table->string('address');
            $table->string('password');
            $table->enum('status', ['active', 'temporary_lock', 'permanently_locked'])->default('active');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('position_id')->constrained('positions')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
