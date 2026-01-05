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
        Schema::create('social_contributors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_contribution_id')->constrained('social_contributions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            // Prevent duplicate entries for the same user and contribution
            $table->unique(['social_contribution_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_contributors');
    }
};
