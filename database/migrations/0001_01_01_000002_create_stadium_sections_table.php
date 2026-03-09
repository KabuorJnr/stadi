<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Stadium sections — each stadium has multiple zones (VIP, Covered,
     * Open Terrace, Goal-end, etc.) with independent capacity tracking.
     * The SVG stadium map renders each section as a clickable region.
     */
    public function up(): void
    {
        Schema::create('stadium_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');                           // "Main Grandstand VIP"
            $table->string('code', 10)->unique();             // "VIP", "CTW", "OTN" ...
            $table->unsignedInteger('capacity');
            $table->unsignedInteger('current_occupancy')->default(0);
            $table->enum('price_tier', ['vip', 'premium', 'regular', 'economy'])->default('regular');
            $table->string('color', 7)->default('#1565c0');   // hex colour for SVG rendering
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('svg_path_id')->nullable();        // matches <path id="..."> in the SVG map
            $table->integer('gate_number')->nullable();       // nearest gate for wayfinding
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('price_tier');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stadium_sections');
    }
};
