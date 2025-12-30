<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Buku Saku (GAP)
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul Panduan
            $table->string('category'); // Fase: Pembibitan, TBM (Belum Menghasilkan), TM (Menghasilkan)
            $table->text('content'); // Isi panduan
            $table->timestamps();
        });

        // 2. Tabel Jadwal (Schedule)
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('activity'); // Contoh: Pemupukan NPK
            $table->date('date'); // Tanggal Rencana
            $table->string('status')->default('pending'); // pending, done, overdue
            $table->timestamps();
        });

        // 3. Tabel Logbook ISPO
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('activity_type'); // Pemupukan/Penyemprotan
            $table->string('material'); // Nama Pupuk/Obat
            $table->string('dose'); // Dosis
            $table->timestamps();
        });

        // 4. Tabel Panen (Harvest)
        Schema::create('harvests', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('weight_kg'); // Berat total
            $table->string('block'); // Lokasi Blok
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guides');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('harvests');
    }
};