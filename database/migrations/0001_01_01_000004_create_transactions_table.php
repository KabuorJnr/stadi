<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('stadium_sections')->nullOnDelete();
            $table->string('mpesa_receipt_number')->nullable()->unique();
            $table->string('merchant_request_id')->nullable();
            $table->string('checkout_request_id')->nullable();
            $table->unsignedInteger('amount');
            $table->string('phone_number');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->enum('channel', ['stk_push', 'c2b'])->default('stk_push');
            $table->json('raw_callback')->nullable();
            $table->timestamps();

            $table->index('mpesa_receipt_number');
            $table->index('checkout_request_id');
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
