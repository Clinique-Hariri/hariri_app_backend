<?php

use App\Constants\BloodType;
use App\Constants\Gender;
use App\Constants\Statuses\UserStatus;
use App\Support\Enum\UserTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('firstname');
      $table->string('lastname')->nullable();
      $table->string('email')->unique();
      $table->string('phone')->nullable()->unique();
      $table->string('avatar')->nullable();
      $table->string('password')->nullable();
      $table->enum('type', UserTypes::all())->default(UserTypes::USER);
      $table->enum('gender', Gender::all())->default(Gender::default());
      $table->enum('blood_type', BloodType::all())->default(BloodType::default());
      $table->date('birthdate')->nullable();
      $table->string('full_address')->nullable();
      $table->timestamp('email_verified_at')->nullable();
      $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
      $table->enum('status', UserStatus::all())->default(UserStatus::ACTIVE);
      $table->boolean('is_insured')->default(false);
      $table->date('insurance_date')->nullable();
      $table->string('insurance_number')->nullable()->unique();
      $table->string('passport_number')->nullable();
      $table->decimal('wallet_balance', 10, 2)->default(0.00);
      $table->string('device_token')->nullable();
      $table->rememberToken();
      $table->timestamps();
    });

    Schema::create('password_reset_tokens', function (Blueprint $table) {
      $table->string('email')->primary();
      $table->string('token');
      $table->timestamp('created_at')->nullable();
    });

    Schema::create('sessions', function (Blueprint $table) {
      $table->string('id')->primary();
      $table->foreignId('user_id')->nullable()->index();
      $table->string('ip_address', 45)->nullable();
      $table->text('user_agent')->nullable();
      $table->longText('payload');
      $table->integer('last_activity')->index();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
    Schema::dropIfExists('password_reset_tokens');
    Schema::dropIfExists('sessions');
  }
};
