<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alternatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('alternate_product_id')->constrained('products')->onDelete('cascade');
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate alternatives
            $table->unique(['product_id', 'alternate_product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternatives');
    }
};