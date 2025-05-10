<?php

use Illuminate\Support\Str;

return [

    'domain' => env('HORIZON_DOMAIN'),

    'path' => env('HORIZON_PATH', 'horizon'),

    'use' => 'default',

    'prefix' => env(
        'HORIZON_PREFIX',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_horizon:'
    ),

    'middleware' => ['web'],

    'waits' => [
        'redis:default' => 60,
    ],

    'trim' => [
        'recent' => 60,
        'pending' => 60,
        'completed' => 60,
        'recent_failed' => 10080,
        'failed' => 10080,
        'monitored' => 10080,
    ],

    'silenced' => [
        // App\Jobs\ExampleJob::class,
    ],

    'metrics' => [
        'trim_snapshots' => [
            'job' => 24,
            'queue' => 24,
        ],
    ],

    'fast_termination' => false,

    'memory_limit' => 64,

    'defaults' => [
        'supervisor-1' => [
            'connection' => 'redis',
            'queue' => ['default'],
            'balance' => 'auto',
            'autoScalingStrategy' => 'time',
            'maxProcesses' => 1,
            'maxTime' => 0,
            'maxJobs' => 0,
            'memory' => 128,
            'tries' => 1,
            'timeout' => 60,
            'nice' => 0,
        ],
    ],

    'environments' => [

        'production' => [

            // Supervisor principal
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => ['default'],
                'balance' => 'auto',
                'processes' => 3,
                'tries' => 3,
            ],

            // Supervisor para comandos de escolas (dinâmico)
            'supervisor-school' => [
                'connection' => 'redis',
                'queue' => ['school_commands', 'school_*'],
                'balance' => 'auto',
                'processes' => 5,
                'tries' => 3,
                'timeout' => 120,
            ],
        ],

        'local' => [

            // Supervisor principal local
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => ['default'],
                'balance' => 'auto',
                'processes' => 1,
                'tries' => 1,
            ],

            // Supervisor para comandos de escola local
            'supervisor-school-dev' => [
                'connection' => 'redis',
                'queue' => ['school_commands', 'school_*'],
                'balance' => 'auto',
                'processes' => 2,
                'tries' => 1,
                'timeout' => 90,
            ],
        ],
    ],
];
