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
        Schema::create('top_up_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('value', 8, 2);
            $table->boolean('is_used')->default(false);
            $table->unsignedBigInteger('used_by')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
            $table->foreign('used_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_up_codes');
    }
};
