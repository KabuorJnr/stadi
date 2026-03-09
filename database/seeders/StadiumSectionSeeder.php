<?php

namespace Database\Seeders;

use App\Models\StadiumSection;
use Illuminate\Database\Seeder;

class StadiumSectionSeeder extends Seeder
{
    /**
     * Seed stadium sections from config/stadium.php presets.
     * Default: Raila Odinga Stadium, Kisumu.
     */
    public function run(): void
    {
        $preset = config('stadium.presets.raila_odinga');

        foreach ($preset['sections'] as $i => $section) {
            StadiumSection::updateOrCreate(
                ['code' => $section['code']],
                [
                    'name' => $section['name'],
                    'capacity' => $section['capacity'],
                    'price_tier' => $section['price_tier'],
                    'color' => $section['color'],
                    'sort_order' => $i + 1,
                    'svg_path_id' => 'section-' . strtolower($section['code']),
                ]
            );
        }
    }
}
