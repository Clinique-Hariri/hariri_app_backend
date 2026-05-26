<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Doctors\Constants\Speciality\Status;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('specialities', function (Blueprint $table) {
      $table->id();
      $table->json('name');
      $table->enum('status', Status::all())->default(Status::ACTIVE);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('specialities');
  }
};
