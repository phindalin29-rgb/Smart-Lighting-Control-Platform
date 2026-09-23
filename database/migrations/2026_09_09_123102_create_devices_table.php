<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('device_uid')->unique();

            $table->string('type')->default('light');
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();

            $table->boolean('status')->default(false);
            $table->boolean('current_state')->default(false);

            $table->timestamp('last_seen_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
