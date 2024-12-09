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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('department');
            $table->integer('current_quantity');
            $table->integer('minimum_quantity')->default(0);
            $table->integer('maximum_quantity');
            $table->string('image_path')->nullable();
            $table->string('barcode')->unique()->nullable();
            $table->string('sku')->unique(); // Stock Keeping Unit
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->string('unit_type')->default('piece'); // piece, box, kg, etc.
            $table->enum('status', [
                'available',
                'low_stock',
                'out_of_stock',
                'unavailable'
            ])->default('available');
            $table->enum('usage_frequency', [
                'daily',
                'weekly',
                'monthly',
                'semester'
            ])->nullable();
            $table->enum('budget_category', [
                'regular',
                'emergency',
                'special',
                'department'
            ])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
