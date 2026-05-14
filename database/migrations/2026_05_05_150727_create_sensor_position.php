<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel hasil kalkulasi TDOA
        Schema::create('tdoa_events', function (Blueprint $table) {
            $table->id();

            $table->string('sensor_1')->nullable();
            $table->string('sensor_2')->nullable();
            $table->string('sensor_3')->nullable();
            $table->string('sensor_4')->nullable();

            // Gunakan presisi 6 (microsecond) untuk akurasi TDOA
            $table->timestamp('t1', 6)->nullable();
            $table->timestamp('t2', 6)->nullable();
            $table->timestamp('t3', 6)->nullable();
            $table->timestamp('t4', 6)->nullable();

            $table->decimal('estimated_latitude', 10, 7)->nullable();
            $table->decimal('estimated_longitude', 10, 7)->nullable();

            $table->float('decibel_1')->nullable();
            $table->float('decibel_2')->nullable();
            $table->float('decibel_3')->nullable();
            $table->float('decibel_4')->nullable();

            $table->enum('status', ['success', 'failed', 'partial'])->default('success');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tdoa_events');
    }
};