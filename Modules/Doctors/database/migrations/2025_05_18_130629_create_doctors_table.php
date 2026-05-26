<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Doctors\Constants\Doctor\Status;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('doctors', function (Blueprint $table) {
      $table->id();
      $table->string('firstname');
      $table->string('lastname')->nullable();
      $table->text('bio')->nullable();
      $table->string('avatar')->nullable();
      $table->enum('status', Status::all())->default(Status::ACTIVE);
      $table->foreignId('speciality_id')->nullable()->constrained('specialities')->nullOnDelete();
      $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('doctors');
  }
};
