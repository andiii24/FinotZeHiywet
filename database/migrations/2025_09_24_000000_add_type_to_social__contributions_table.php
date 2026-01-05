<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('social_contributions', function (Blueprint $table) {
            $table->string('type')->default('open')->after('location'); // 'open' or 'fixed'
            $table->decimal('amount', 15, 2)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('social_contributions', function (Blueprint $table) {
            // Revert amount to nullable if it previously was
            $table->decimal('amount', 15, 2)->nullable()->change();
            $table->dropColumn('type');
        });
    }
};


