<?php

return [

    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'usuarios',
    ],

    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'usuarios',
        ],
    ],

    'providers' => [
        // Apunta al modelo Usuario (no al User por defecto de Laravel)
        'usuarios' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Usuario::class,
        ],
    ],

    'passwords' => [
        'usuarios' => [
            'provider' => 'usuarios',
            'table'    => 'password_resets',
            'expire'   => 10,   // minutos — igual que ResetPasswordController
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];