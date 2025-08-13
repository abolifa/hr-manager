<?php
return [
    'mode' => 'utf-8',
    'debug' => false,
    'debugfonts' => false,
    'format' => 'A4',
    'author' => '',
    'subject' => '',
    'keywords' => '',
    'creator' => 'Laravel Pdf',
    'display_mode' => 'fullpage',
    'tempDir' => storage_path('app/mpdf_temp'),
    'pdf_a' => false,
    'pdf_a_auto' => false,
    'icc_profile_path' => '',
    'font_path' => public_path('fonts/'),
    'font_data' => [
        'tajawal' => [
            'R' => 'Tajawal-Regular.ttf',
            'B' => 'Tajawal-Bold.ttf',
            'useOTL' => 0xFF,   // for Arabic shaping
            'useKashida' => 75, // optional
        ],
    ],
    'default_font' => 'tajawal',
];
