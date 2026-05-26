<?php

use Modules\Ads\Constants\AdType;
use Modules\Ads\Constants\Status;
use Modules\Ads\Constants\UserType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', AdType::all())->default(AdType::NONE);
            $table->text('link')->nullable();
            $table->date('expiration_date');
            $table->enum('status', Status::all())->default(Status::ACTIVE);
            $table->json('user_types')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
