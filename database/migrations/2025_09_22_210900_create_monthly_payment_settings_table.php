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
        Schema::create('monthly_payment_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('employed_amount', 10, 2)->default(0);
            $table->decimal('unemployed_amount', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default values
        DB::table('monthly_payment_settings')->insert([
            'employed_amount' => 100.00,
            'unemployed_amount' => 50.00,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_payment_settings');
    }
};
