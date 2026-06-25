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
    Schema::create('vector_mappings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('statistic_id')->constrained('statistics')->cascadeOnDelete();
        $table->integer('origin_point')->nullable();
        $table->integer('target_point')->nullable();
        $table->float('origin_x')->nullable();
        $table->float('origin_y')->nullable();
        $table->float('target_x')->nullable();
        $table->float('target_y')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vector_mappings');
    }
};
