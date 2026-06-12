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
        Schema::table('inquiries', function (Blueprint $table) {
            // Kdy poptávku vyřídil vedoucí v administraci (≠ sent_at, což je odeslání e-mailu).
            $table->timestamp('handled_at')->nullable()->index()->after('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropIndex(['handled_at']);
            $table->dropColumn('handled_at');
        });
    }
};
