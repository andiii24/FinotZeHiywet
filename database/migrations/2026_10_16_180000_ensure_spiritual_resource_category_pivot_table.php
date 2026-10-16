<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ensure the spiritual resource ↔ category pivot exists.
     * Safe to run when the original pivot migration failed or was skipped on a host.
     */
    public function up(): void
    {
        if (Schema::hasTable('spiritual_resource_category')) {
            return;
        }

        Schema::create('spiritual_resource_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spiritual_resource_id');
            $table->unsignedBigInteger('spiritual_resource_category_id');
            $table->timestamps();

            $table->foreign('spiritual_resource_id', 'src_resource_fk')
                ->references('id')
                ->on('spiritual_resources')
                ->cascadeOnDelete();

            $table->foreign('spiritual_resource_category_id', 'src_category_fk')
                ->references('id')
                ->on('spiritual_resource_categories')
                ->cascadeOnDelete();

            $table->unique(
                ['spiritual_resource_id', 'spiritual_resource_category_id'],
                'src_resource_category_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not drop here — original migration owns the table lifecycle.
    }
};
