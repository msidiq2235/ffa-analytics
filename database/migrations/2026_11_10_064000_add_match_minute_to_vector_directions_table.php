<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vector_directions', function (Blueprint $table) {
            $table->unsignedSmallInteger('match_minute')->nullable()->after('input_time');
        });
    }

    public function down(): void
    {
        Schema::table('vector_directions', function (Blueprint $table) {
            $table->dropColumn('match_minute');
        });
    }
};
