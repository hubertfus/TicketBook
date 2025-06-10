<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE tickets DROP CONSTRAINT IF EXISTS tickets_category_check');

        Schema::table('tickets', function (Blueprint $table) {
            $table->string('category', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->enum('category', ['standard', 'vip', 'student'])->change();
        });
        DB::statement("
            ALTER TABLE tickets
            ADD CONSTRAINT tickets_category_check
            CHECK (category IN ('standard','vip','student'))
        ");
    }
};
