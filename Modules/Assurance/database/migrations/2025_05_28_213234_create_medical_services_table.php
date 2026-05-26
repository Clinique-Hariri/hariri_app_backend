<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Assurance\Constants\MedicalService\Status;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('medical_services', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->decimal('price', 10, 2);
      $table->enum('status', Status::all())->default(Status::ACTIVE);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('medical_services');
  }
};
