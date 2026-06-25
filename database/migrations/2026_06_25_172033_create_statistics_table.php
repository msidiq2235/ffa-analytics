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
    Schema::create('statistics', function (Blueprint $table) {
        $table->id();
        $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
        $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
        $table->foreignId('analyst_id')->constrained('users')->cascadeOnDelete();
        $table->integer('minute')->nullable();
        $table->timestamp('input_time')->nullable();
        $table->integer('shooting_on_target')->default(0);
        $table->integer('goal')->default(0);
        $table->integer('shots')->default(0);
        $table->integer('shooting_off_target')->default(0);
        $table->integer('penalty_goal')->default(0);
        $table->integer('shot_on_target_alternate')->default(0);
        $table->integer('assist')->default(0);
        $table->integer('chance_created')->default(0);
        $table->integer('successful_passes')->default(0);
        $table->integer('successful_crosses')->default(0);
        $table->integer('dribble')->default(0);
        $table->integer('successful_dribble')->default(0);
        $table->integer('unsuccessful_dribble')->default(0);
        $table->integer('tackles')->default(0);
        $table->integer('fouls_committed')->default(0);
        $table->integer('interceptions')->default(0);
        $table->integer('dribbled_past')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
