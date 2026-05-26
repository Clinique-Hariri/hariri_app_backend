<?php

use Illuminate\Support\Facades\Schema;
use Modules\Products\Constants\Status;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->string('name');
            $table->string('scientific_name')->nullable();
            $table->string('barcode')->nullable()->unique();
            $table->decimal('price', 10, 2);
            $table->integer('unit_count')->default(1);
            $table->longText('description')->nullable();
            $table->enum('status', Status::all())->default(Status::default());
            $table->boolean('is_compensable')->default(false);
            $table->boolean('with_prescription')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
