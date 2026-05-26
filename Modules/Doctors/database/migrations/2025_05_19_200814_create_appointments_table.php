<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Doctors\Constants\Appointment\Status;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('appointments', function (Blueprint $table) {
      $table->id();
      $table->string('patient_name');
      $table->string('patient_phone')->nullable();
      $table->string('patient_age')->nullable();
      $table->date('date');
      $table->time('time')->nullable();
      $table->enum('status', Status::all())->default(Status::PENDING);
      $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('appointments');
  }
};
