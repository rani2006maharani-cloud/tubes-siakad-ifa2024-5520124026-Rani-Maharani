// database/migrations/2024_01_01_000001_create_dosens_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dosen', 20)->unique();
            $table->string('nama_dosen', 100);
            $table->string('nidn', 20)->unique();
            $table->string('email')->unique();
            $table->string('no_telepon', 15)->nullable();
            $table->string('alamat')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('pendidikan_terakhir', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};