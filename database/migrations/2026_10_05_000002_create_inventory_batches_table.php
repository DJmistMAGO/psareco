<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(0);
            $table->date('expiration_date')->nullable();
            $table->timestamps();
        });

        DB::table('inventories')
            ->where('quantity', '>', 0)
            ->orderBy('id')
            ->chunkById(500, function ($inventories): void {
                foreach ($inventories as $inventory) {
                    DB::table('inventory_batches')->insert([
                        'inventory_id' => $inventory->id,
                        'quantity' => $inventory->quantity,
                        'expiration_date' => $inventory->expiration_date,
                        'created_at' => $inventory->created_at,
                        'updated_at' => $inventory->updated_at,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_batches');
    }
};
