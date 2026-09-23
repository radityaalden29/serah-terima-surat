<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pencatat aktivitas (audit trail) untuk setiap aksi
     * tambah / edit / hapus surat yang terjadi di sistem.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Ditambahkan / Diperbarui / Dihapus
            $table->string('aksi');

            $table->string('no_agenda');

            $table->string('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
