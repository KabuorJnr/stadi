<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('event_date');
            $table->unsignedInteger('base_ticket_price');     // KES — economy tier price
            $table->unsignedInteger('max_capacity')->default(20000);
            $table->unsignedInteger('current_attendance')->default(0);
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->boolean('ticket_sales_open')->default(true);
            $table->string('home_team')->nullable();
            $table->string('away_team')->nullable();
            $table->string('competition')->nullable();       // e.g. "KPL", "FKF Cup", "AFCON Qualifier"
            $table->string('poster_url')->nullable();
            $table->timestamps();

            $table->index('event_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
