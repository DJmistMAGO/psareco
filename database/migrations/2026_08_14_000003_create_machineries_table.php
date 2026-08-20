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
        Schema::create('machineries', function (Blueprint $table) {
            $table->id();
            $table->string('machinery_name');
            $table->string('model');
            $table->string('serial_number')->unique();
            $table->string('price');
            $table->string('image_path');
            $table->enum('status', ['Available', 'Reserved', 'In Use', 'Under Maintenance', 'Unavailable'])->default('Available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machinery');
    }
};
