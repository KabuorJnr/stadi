<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('stadium_sections')->nullOnDelete();
            $table->string('qr_hash', 128)->unique();
            $table->enum('status', ['active', 'scanned', 'cancelled'])->default('active');
            $table->timestamps();

            $table->index(['event_id', 'status']);
            $table->index('qr_hash');
            $table->index(['section_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
