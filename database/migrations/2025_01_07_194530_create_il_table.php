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
        Schema::create('Il', function (Blueprint $table) {
            $table->id();
            $table->string('Kod')->unique();
            $table->string('IlAdi', 50)->unique();
            $table->boolean('Durum')->default(true);
            $table->string('Aciklama',300)->nullable();
            $table->timestamp('EklenmeTarihi')->useCurrent();
            $table->timestamp('DuzenlenmeTarihi')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('il');
    }
};
