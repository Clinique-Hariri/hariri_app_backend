<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Assurance\Constants\HomeCheckup\Status;
use Modules\Assurance\Constants\HomeCheckup\VisitType;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('home_checkup_requests', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      $table->foreignId('location_id')->constrained('locations')->restrictOnDelete();
      $table->string('patient_number')->nullable();
      $table->text('notes')->nullable();
      $table->date('preferred_date');
      $table->time('preferred_time')->nullable();
      $table->enum('status', Status::all())->default(Status::PENDING);
      $table->enum('visit_type', VisitType::all())->nullable();
      $table->timestamp('reviewed_at')->nullable();
      $table->text('rejection_reason')->nullable();
      $table->decimal('base_price', 10, 2)->default(0);
      $table->decimal('total_price', 10, 2)->default(0);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('home_checkup_requests');
  }
};
