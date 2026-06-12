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
        Schema::create('schedule_overrides', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->string('kind'); // zruseno | extra
            $table->string('type'); // Judo / Taijutsu / vlastní
            $table->string('place')->nullable();
            $table->string('loc')->nullable();
            $table->string('time'); // „16:30–18:00"
            $table->string('form')->nullable(); // mapování na možnost ve formuláři
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_overrides');
    }
};
