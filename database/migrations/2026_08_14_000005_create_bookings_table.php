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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->nullable()->constrained('machineries')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('days')->default(1);
            $table->decimal('total_hours', 8, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Completed'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
