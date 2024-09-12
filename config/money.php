<?php

return [

    'defaults' => [
        'currency' => env('MONEY_DEFAULTS_CURRENCY', 'BRL'),
        'convert' => env('MONEY_DEFAULTS_CONVERT', false),
    ],

    'currencies' => [
        'BRL' => [
            'name' => 'Brazilian Real',
            'code' => 986,
            'precision' => 2,
            'subunit' => 100,
            'symbol' => 'R$',
            'symbol_first' => true,
            'decimal_mark' => ',',
            'thousands_separator' => '.',
        ],
    ],

];
