<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
public function up()
{
Schema::create('vector_directions', function (Blueprint $table) {
$table->id();
$table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
$table->foreignId('player_id')->constrained('players')->onDelete('cascade');
$table->foreignId('analyst_id')->constrained('users')->onDelete('cascade');
$table->string('action_type');
$table->float('start_x');
$table->float('start_y');
$table->float('end_x');
$table->float('end_y');
$table->timestamps();
});
}
public function down()
{
Schema::dropIfExists('vector_directions');
}
};