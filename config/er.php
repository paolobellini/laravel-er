<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Output Path
    |--------------------------------------------------------------------------
    |
    | Where the generated files will be saved, relative to the project root.
    |
    */
    'output_path' => base_path(),

    /*
    |--------------------------------------------------------------------------
    | Excluded Tables
    |--------------------------------------------------------------------------
    |
    | Tables that should be excluded from the diagram.
    |
    */
    'excluded_tables' => [
        'migrations',
        'failed_jobs',
        'password_reset_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
    ],

];
