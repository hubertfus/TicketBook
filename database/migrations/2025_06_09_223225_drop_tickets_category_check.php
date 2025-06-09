<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            DB::statement('ALTER TABLE tickets DROP CONSTRAINT IF EXISTS tickets_category_check');

            DB::statement('ALTER TABLE tickets ALTER COLUMN category TYPE VARCHAR');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE tickets ALTER COLUMN category TYPE VARCHAR;
            ALTER TABLE tickets ADD CONSTRAINT tickets_category_check CHECK (category IN ('standard','vip','student'))
        ");
    }
};
