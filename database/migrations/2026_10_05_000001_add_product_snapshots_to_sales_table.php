<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('product_name', 100)->nullable()->after('product_id');
            $table->string('product_unit', 20)->nullable()->after('product_name');
        });

        DB::table('sales')
            ->whereNotNull('product_id')
            ->orderBy('id')
            ->chunkById(500, function ($sales): void {
                foreach ($sales as $sale) {
                    $product = DB::table('inventories')
                        ->where('id', $sale->product_id)
                        ->first(['name', 'unit']);

                    if ($product) {
                        DB::table('sales')
                            ->where('id', $sale->id)
                            ->update([
                                'product_name' => $product->name,
                                'product_unit' => $product->unit,
                            ]);
                    }
                }
            });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['product_name', 'product_unit']);
        });
    }
};
