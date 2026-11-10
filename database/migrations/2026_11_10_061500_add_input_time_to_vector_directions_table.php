<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vector_directions', function (Blueprint $table) {
            $table->timestamp('input_time')->nullable()->after('action_type');
        });
    }

    public function down(): void
    {
        Schema::table('vector_directions', function (Blueprint $table) {
            $table->dropColumn('input_time');
        });
    }
};
