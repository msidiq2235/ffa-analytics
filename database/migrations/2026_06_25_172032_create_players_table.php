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
    Schema::create('players', function (Blueprint $table) {
        $table->id();
        $table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
        $table->string('name', 255);
        $table->string('popular_name', 255)->nullable();
        $table->string('birth_place_date', 255)->nullable();
        $table->string('nik', 50)->nullable();
        $table->integer('jersey_number')->nullable();
        $table->integer('age_group')->nullable();
        $table->string('gender', 15)->nullable();
        $table->string('country', 255)->nullable();
        $table->string('dominant_foot', 50)->nullable();
        $table->decimal('height_cm', 5, 2)->nullable();
        $table->decimal('weight_kg', 5, 2)->nullable();
        $table->string('address', 255)->nullable();
        $table->string('province', 255)->nullable();
        $table->string('phone', 20)->nullable();
        $table->string('email', 255)->nullable();
        $table->string('photo_path', 255)->nullable();
        $table->string('family_card_path', 255)->nullable();
        $table->string('club_letter_path', 255)->nullable();
        $table->string('birth_certificate_path', 255)->nullable();
        $table->string('report_card_path', 255)->nullable();
        $table->string('privacy_consent_letter_path', 255)->nullable();
        $table->string('document_path', 255)->nullable();
        $table->string('status', 50)->nullable();
        $table->text('verification_data')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
