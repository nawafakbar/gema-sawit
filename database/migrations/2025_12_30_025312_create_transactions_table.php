<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('type', ['income', 'expense']); // Pemasukan atau Pengeluaran
            $table->string('category'); // Contoh: Penjualan Sawit, Beli Pupuk
            $table->decimal('amount', 15, 2); // Jumlah Uang (Support angka besar)
            $table->string('description')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
