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
        Schema::create('ongkirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kota_asal_id')->constrained('kotas')->onDelete('cascade');
            $table->foreignId('kota_tujuan_id')->constrained('kotas')->onDelete('cascade');
            $table->decimal('tarif_per_kg', 10, 2);
            $table->timestamps();

            // Index untuk pencarian cepat
            $table->index(['kota_asal_id', 'kota_tujuan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ongkirs');
    }
};
