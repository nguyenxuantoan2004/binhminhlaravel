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
        //chi tiết phiếu nhập kho
        Schema::create('inventory_receipt_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_receipt_id')->constrained('inventory_receipts');
            $table->foreignId('motorbike_id')->constrained('motorbikes');
            $table->integer('quantity_received');
            $table->decimal('total_amount_received', 10, 2);
            $table->decimal('purchase_price', 10, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_receipt_details');
    }
};
