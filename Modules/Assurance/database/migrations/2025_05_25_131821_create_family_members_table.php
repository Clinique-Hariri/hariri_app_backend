<?php

use App\Constants\BloodType;
use App\Constants\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Assurance\Constants\FamilyMember\Status;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('family_members', function (Blueprint $table) {
      $table->id();
      $table->string('firstname');
      $table->string('lastname')->nullable();
      $table->date('birthdate')->nullable();
      $table->string('phone')->nullable();
      $table->enum('gender', Gender::all())->default(Gender::default());
      $table->enum('blood_type', BloodType::all())->nullable();
      $table->string('family_relation')->nullable();
      $table->string('avatar')->nullable();
      $table->string('insurance_number')->nullable()->unique();
      $table->enum('status', Status::all())->default(Status::ACTIVE);

      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('family_members');
  }
};
