<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stadium Configuration
    |--------------------------------------------------------------------------
    | Core settings for the stadium. Each stadium in Kenya has different
    | sections:  VIP, Covered, Open Terrace, etc.  The graphical map
    | renders sections defined in the stadium_sections DB table.
    */
    'name' => env('STADIUM_NAME', 'Raila Odinga Stadium'),
    'max_capacity' => (int) env('STADIUM_MAX_CAPACITY', 20000),
    'gate_count' => (int) env('STADIUM_GATE_COUNT', 4),
    'scanner_api_key' => env('SCANNER_API_KEY'),

    /*
    | Kenyan Stadium Presets  — used by the seeder to bootstrap section data.
    | Capacity numbers based on official KPL / FKF figures.
    */
    'presets' => [
        'raila_odinga' => [
            'name' => 'Raila Odinga Stadium',
            'city' => 'Kisumu',
            'total' => 20000,
            'sections' => [
                ['name' => 'Main Grandstand VIP',   'code' => 'VIP',  'capacity' => 1500, 'price_tier' => 'vip',     'color' => '#c9a84c'],
                ['name' => 'Main Grandstand',       'code' => 'MGS',  'capacity' => 3500, 'price_tier' => 'premium', 'color' => '#2e7d32'],
                ['name' => 'Covered Terrace West',  'code' => 'CTW',  'capacity' => 3000, 'price_tier' => 'regular', 'color' => '#1565c0'],
                ['name' => 'Covered Terrace East',  'code' => 'CTE',  'capacity' => 3000, 'price_tier' => 'regular', 'color' => '#1565c0'],
                ['name' => 'Open Terrace North',    'code' => 'OTN',  'capacity' => 3000, 'price_tier' => 'economy', 'color' => '#7b8794'],
                ['name' => 'Open Terrace South',    'code' => 'OTS',  'capacity' => 3000, 'price_tier' => 'economy', 'color' => '#7b8794'],
                ['name' => 'Terrace Behind Goal A', 'code' => 'TGA',  'capacity' => 1500, 'price_tier' => 'economy', 'color' => '#9e9e9e'],
                ['name' => 'Terrace Behind Goal B', 'code' => 'TGB',  'capacity' => 1500, 'price_tier' => 'economy', 'color' => '#9e9e9e'],
            ],
        ],
        'kasarani' => [
            'name' => 'Moi International Sports Centre, Kasarani',
            'city' => 'Nairobi',
            'total' => 60000,
            'sections' => [
                ['name' => 'VIP Pavilion',          'code' => 'VIP',  'capacity' => 3000,  'price_tier' => 'vip',     'color' => '#c9a84c'],
                ['name' => 'Main Stand Premium',    'code' => 'MSP',  'capacity' => 7000,  'price_tier' => 'premium', 'color' => '#2e7d32'],
                ['name' => 'Main Stand Regular',    'code' => 'MSR',  'capacity' => 10000, 'price_tier' => 'regular', 'color' => '#1565c0'],
                ['name' => 'Covered East',          'code' => 'CVE',  'capacity' => 10000, 'price_tier' => 'regular', 'color' => '#1565c0'],
                ['name' => 'Open North Terrace',    'code' => 'ONT',  'capacity' => 10000, 'price_tier' => 'economy', 'color' => '#7b8794'],
                ['name' => 'Open South Terrace',    'code' => 'OST',  'capacity' => 10000, 'price_tier' => 'economy', 'color' => '#7b8794'],
                ['name' => 'Goal End A',            'code' => 'GEA',  'capacity' => 5000,  'price_tier' => 'economy', 'color' => '#9e9e9e'],
                ['name' => 'Goal End B',            'code' => 'GEB',  'capacity' => 5000,  'price_tier' => 'economy', 'color' => '#9e9e9e'],
            ],
        ],
        'nyayo' => [
            'name' => 'Nyayo National Stadium',
            'city' => 'Nairobi',
            'total' => 30000,
            'sections' => [
                ['name' => 'VIP Wing',              'code' => 'VIP',  'capacity' => 2000, 'price_tier' => 'vip',     'color' => '#c9a84c'],
                ['name' => 'Main Grandstand',       'code' => 'MGS',  'capacity' => 5000, 'price_tier' => 'premium', 'color' => '#2e7d32'],
                ['name' => 'Covered Terrace A',     'code' => 'CTA',  'capacity' => 5000, 'price_tier' => 'regular', 'color' => '#1565c0'],
                ['name' => 'Covered Terrace B',     'code' => 'CTB',  'capacity' => 5000, 'price_tier' => 'regular', 'color' => '#1565c0'],
                ['name' => 'Open Terrace North',    'code' => 'OTN',  'capacity' => 5000, 'price_tier' => 'economy', 'color' => '#7b8794'],
                ['name' => 'Open Terrace South',    'code' => 'OTS',  'capacity' => 5000, 'price_tier' => 'economy', 'color' => '#7b8794'],
                ['name' => 'Goal Curve A',          'code' => 'GCA',  'capacity' => 1500, 'price_tier' => 'economy', 'color' => '#9e9e9e'],
                ['name' => 'Goal Curve B',          'code' => 'GCB',  'capacity' => 1500, 'price_tier' => 'economy', 'color' => '#9e9e9e'],
            ],
        ],
    ],

    'price_tiers' => [
        'vip'     => ['label' => 'VIP',     'multiplier' => 5.0],
        'premium' => ['label' => 'Premium', 'multiplier' => 3.0],
        'regular' => ['label' => 'Regular', 'multiplier' => 1.5],
        'economy' => ['label' => 'Economy', 'multiplier' => 1.0],
    ],
];
