<?php

return [
    'name' => 'Luxurize',
    'description' => 'Luxurize It',
    'keywords' => 'luxurize it, make it luxury, luxury products, better, gonna be a good day',
    'email' => 'email@provider.com',
//    'address' => [
//        'street' => '123 Example St',
//        'locality' => 'Town',
//        'region' => 'State',
//        'zip' => '01234',
//        'country' => 'US',
//    ],
    'ld_type' => 'WebPage',
//    'phone' => '+11234567890',
    'logo' => config('app.image_url') . '/layout/luxurize_logo_sm.webp',
    'logos' => [
//        'svg' => config('app.image_url') . '/layout/cstash.svg',
        'png' => config('app.image_url') . '/layout/luxurize_logo_sm.webp',
        'square' => config('app.image_url') . '/layout/luxurize_logo_sm.webp',
    ],
];
