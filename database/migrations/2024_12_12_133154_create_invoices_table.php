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
        //hóa đơn
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('expected_return_date')->nullable();
            $table->timestamp('actual_return_date')->nullable();
            $table->decimal('vat_fee', 10, 2);
            $table->string('phone');
            $table->string('email');
            $table->string('total_rental_duration')->nullable();
            $table->decimal('additional_fees', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->string('motorbike_receipt_method')->nullable();
            $table->string('motorbike_pickup_location')->nullable();
            $table->enum('status', ['pending', 'on_loan', 'confirmed', 'delivering', 'waiting_for_pickup', 'picked_up','completed','cancelled'])->default('pending');
            $table->foreignId('motorbike_id')->constrained('motorbikes')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
