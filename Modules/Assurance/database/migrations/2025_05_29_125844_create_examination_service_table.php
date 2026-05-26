<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('examination_service', function (Blueprint $table) {
      $table->id();
      $table->string('service_name');
      $table->decimal('service_price', 10, 2);
      $table->foreignId('medical_service_id')->nullable()->constrained('medical_services')->nullOnDelete();
      $table->foreignId('medical_examination_id')->constrained('medical_examinations')->cascadeOnDelete();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('examination_service');
  }
};
