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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('organizer');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->enum('type', ['concert', 'sport', 'standup', 'festival', 'other']);
            $table->boolean('last_minute')->default(false);
            $table->integer('totalTickets');
            $table->integer('ticketSold')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
