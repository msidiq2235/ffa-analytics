<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('statistics', function (Blueprint $table) {
            $table->unsignedInteger('shots_faced')->default(0);
            $table->unsignedInteger('goals_conceded')->default(0);
            $table->unsignedInteger('clean_sheets')->default(0);
            $table->unsignedInteger('saves')->default(0);
            $table->unsignedInteger('penalty_saves')->default(0);
            $table->unsignedInteger('high_claims')->default(0);
            $table->unsignedInteger('punches')->default(0);
            $table->unsignedInteger('sweeper_actions')->default(0);
            $table->unsignedInteger('catches')->default(0);
            $table->unsignedInteger('crosses_stopped')->default(0);
            $table->unsignedInteger('accurate_goal_kicks')->default(0);
            $table->unsignedInteger('unsuccessful_passes')->default(0);
            $table->unsignedInteger('accurate_throws')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('statistics', function (Blueprint $table) {
            $table->dropColumn([
                'shots_faced',
                'goals_conceded',
                'clean_sheets',
                'saves',
                'penalty_saves',
                'high_claims',
                'punches',
                'sweeper_actions',
                'catches',
                'crosses_stopped',
                'accurate_goal_kicks',
                'unsuccessful_passes',
                'accurate_throws',
            ]);
        });
    }
};
