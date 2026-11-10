<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('token', 512)->unique();
            $table->string('platform', 20)->nullable();
            $table->timestamps();
        });

        $now = now();

        DB::table('users')
            ->whereNotNull('device_token')
            ->where('device_token', '!=', '')
            ->select('id', 'device_token')
            ->orderBy('id')
            ->each(function ($user) use ($now) {
                DB::table('device_tokens')->insertOrIgnore([
                    'user_id' => $user->id,
                    'token' => $user->device_token,
                    'platform' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('device_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('device_token')->nullable();
        });

        DB::table('device_tokens')
            ->orderBy('updated_at')
            ->each(function ($deviceToken) {
                DB::table('users')
                    ->where('id', $deviceToken->user_id)
                    ->update(['device_token' => $deviceToken->token]);
            });

        Schema::dropIfExists('device_tokens');
    }
};
