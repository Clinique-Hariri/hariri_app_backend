<?php

use Illuminate\Support\Facades\Schema;
use Modules\Locations\Constants\Status;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('locations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      $table->string('address')->nullable();
      $table->decimal('longitude', 10, 7);
      $table->decimal('latitude', 10, 7);
      $table->enum('status', Status::all())->default(Status::ACTIVE);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('locations');
  }
};
