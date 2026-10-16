<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('home_checkup_request_services', function (Blueprint $table) {
      $table->id();
      $table->foreignId('request_id')->constrained('home_checkup_requests')->cascadeOnDelete();
      $table->foreignId('home_service_id')->nullable()->constrained('home_services')->nullOnDelete();
      $table->string('service_name');
      $table->decimal('service_price', 10, 2);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('home_checkup_request_services');
  }
};
