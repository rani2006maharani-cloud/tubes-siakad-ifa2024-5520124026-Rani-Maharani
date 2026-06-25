// database/migrations/2024_01_01_000005_create_krs_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained()->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained()->onDelete('cascade');
            $table->year('tahun_akademik');
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->date('tanggal_pengambilan');
            $table->enum('status', ['Draft', 'Disetujui', 'Batal'])->default('Draft');
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            // Unique constraint untuk mencegah duplikasi
            $table->unique(['mahasiswa_id', 'jadwal_id', 'tahun_akademik', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};