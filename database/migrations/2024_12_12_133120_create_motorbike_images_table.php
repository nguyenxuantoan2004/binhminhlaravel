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
        Schema::create('motorbike_images', function (Blueprint $table) {
            $table->id();
            $table->string("file_name");
            $table->foreignId("motorbike_id")->constrained("motorbikes")->onDelete("cascade");
            $table->integer("pin");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorbike_images');
    }
};
