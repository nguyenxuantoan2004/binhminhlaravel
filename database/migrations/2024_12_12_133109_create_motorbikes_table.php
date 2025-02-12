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
        //xe
        Schema::create('motorbikes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('quantity');
            $table->year('manufacture_year');
            $table->string('color');
            $table->string('vehicle_condition'); // tình trạng xe
            $table->enum('status', ['draft', 'public', 'pending', 'private', "maintenance"])->default('draft');
            $table->decimal('rental_price', 10, 2);
            $table->foreignId('category_motorbike_id')->constrained('category_motorbikes')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
           
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorbikes');
    }
};
