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
    Schema::create('surats', function (Blueprint $table) {

        $table->id();

        $table->string('no_agenda')->unique();

        $table->enum('jenis',[
            'Surat Masuk',
            'Surat Keluar'
        ]);

        $table->string('pengirim');

        $table->string('penerima');

        $table->string('perihal');

        $table->date('tanggal');

        $table->string('kategori');

        $table->string('sifat');

        $table->string('lampiran')->nullable();

        $table->text('keterangan')->nullable();

        $table->enum('status',[
            'Menunggu',
            'Diterima',
            'Dikirim'
        ])->default('Menunggu');

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
