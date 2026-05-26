<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('medical_examinations', function (Blueprint $table) {
      $table->id();
      $table->decimal('total_price');
      $table->date('date');
      $table->boolean('is_paid')->default(false);
      $table->foreignId('family_member_id')->constrained('family_members')->cascadeOnDelete();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('medical_examinations');
  }
};
