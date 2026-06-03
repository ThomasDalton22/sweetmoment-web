<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->enum('jenispemesanan',['Wedding_Organizer', 'Party_Organizer'])->default('Wedding_Organizer');
            $table->string('nama_pemesan');
            $table->string('nama_vendor');
            $table->date('tanggal_acara');
            $table->text('catatan')->nullable();
            $table->bigInteger('harga');
            $table->enum('status',['Unpaid', 'Paid'])->default('Unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
