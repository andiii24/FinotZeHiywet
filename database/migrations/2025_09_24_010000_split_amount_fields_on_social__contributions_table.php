<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('social_contributions', function (Blueprint $table) {
            $table->decimal('target_amount', 15, 2)->default(0)->after('type');
            $table->decimal('single_amount', 15, 2)->nullable()->after('target_amount');
        });

        // Migrate existing data from legacy 'amount' to 'target_amount'
        if (Schema::hasColumn('social_contributions', 'amount')) {
            DB::table('social_contributions')->update([
                'target_amount' => DB::raw('COALESCE(amount, 0)')
            ]);

            Schema::table('social_contributions', function (Blueprint $table) {
                $table->dropColumn('amount');
            });
        }
    }

    public function down(): void
    {
        Schema::table('social_contributions', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->nullable();
        });

        // Move back data
        DB::table('social_contributions')->update([
            'amount' => DB::raw('COALESCE(target_amount, 0)')
        ]);

        Schema::table('social_contributions', function (Blueprint $table) {
            $table->dropColumn(['target_amount', 'single_amount']);
        });
    }
};


