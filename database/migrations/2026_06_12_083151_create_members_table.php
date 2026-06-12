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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('age');
            $table->string('group')->index();
            $table->string('parent_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('member_since')->nullable();
            $table->string('belt')->nullable();
            $table->string('status')->default('aktivni')->index();
            $table->text('note')->nullable();
            $table->foreignId('inquiry_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
