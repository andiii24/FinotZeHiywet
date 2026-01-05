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
        Schema::table('monthly_payment_settings', function (Blueprint $table) {
            $table->date('start_month')->nullable()->after('unemployed_amount');
        });

        // Update existing record with default start month
        DB::table('monthly_payment_settings')->update([
            'start_month' => '2024-09-01',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_payment_settings', function (Blueprint $table) {
            $table->dropColumn('start_month');
        });
    }
};
