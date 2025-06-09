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
        Schema::create('kartu_disposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lembar_disposisi_sekda_id')->nullable();
            $table->foreignId('lembar_disposisi_asda_id')->nullable();
            $table->foreignId('surat_id');
            $table->string('index')->nullable();
            $table->date('tgl_penyelesaian')->nullable();
            $table->enum('keputusan', ['intruksi', 'informasi']);
            $table->text('catatan')->nullable();
            $table->string('diteruskan');
            $table->string('file_nota_dinas')->nullable()->default(null);
            $table->dateTime('tgl_diterima_asda')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_disposisi');
    }
};
