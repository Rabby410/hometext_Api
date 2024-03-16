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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('attribute_value_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('attribute_math_sign')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('attribute_number')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('shop_quantities')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('attribute_weight')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('attribute_mesarment')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('attribute_cost')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('attribute_cost')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('attribute_quantity')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('attribute_weight')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('attribute_mesarment')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
