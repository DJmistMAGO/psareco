<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'start_day_type')) {
                $table->enum('start_day_type', ['Whole Day', 'Morning Half Day', 'Afternoon Half Day'])
                    ->default('Whole Day');
            }

            if (! Schema::hasColumn('bookings', 'end_day_type')) {
                $table->enum('end_day_type', ['Whole Day', 'Morning Half Day', 'Afternoon Half Day'])
                    ->default('Whole Day');
            }
        });
    }

    public function down(): void
    {
        $columns = array_filter(
            ['start_day_type', 'end_day_type'],
            fn(string $column): bool => Schema::hasColumn('bookings', $column)
        );

        if ($columns !== []) {
            Schema::table('bookings', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
