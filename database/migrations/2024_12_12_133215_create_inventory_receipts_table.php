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
        //phiếu nhập kho
        Schema::create('inventory_receipts', function (Blueprint $table) {
            $table->id();
            $table->timestamp('entry_date');
            $table->integer('quantity_received');
            $table->decimal('total_amount_received', 10, 2);
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_receipts');
    }
};
