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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('meta')->nullable();
            $table->string('group');
            $table->string('type')->default('file');
            $table->string('filename')->nullable();
            $table->string('url')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->unsignedInteger('downloads')->default(0);
            $table->boolean('visible')->default(true)->index();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();

            $table->index(['group', 'sort']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
