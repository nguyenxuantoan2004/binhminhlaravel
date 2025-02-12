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
        //loại xe
        Schema::create('category_motorbikes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['draft', 'public', 'pending', 'private'])->default('draft');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_motorbikes');
    }
};
