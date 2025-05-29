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
        Schema::create('data_pemilih', function (Blueprint $table) {
            $table->id();
            $table->string('kecamatan');
            $table->string('kelurahan');
            $table->integer('dpt_laki_laki');
            $table->integer('dpt_perempuan');
            $table->integer('dpt_total');
            $table->integer('suara_sah');
            $table->integer('suara_tidak_sah');
            $table->integer('suara_total');
            $table->decimal('partisipasi', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pemilih');
    }
};
