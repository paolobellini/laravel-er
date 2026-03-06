<?php

use PaoloBellini\LaravelEr\Renderers\DbDiagramRenderer;
use PaoloBellini\LaravelEr\Renderers\MermaidRenderer;

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
    | Output Filename
    |--------------------------------------------------------------------------
    |
    | The name of the generated file (without extension).
    |
    */
    'output_filename' => 'er-diagram',

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

    /*
    |--------------------------------------------------------------------------
    | Available Renderers
    |--------------------------------------------------------------------------
    |
    | The format used to produce the diagram
    |
    */
    'renderer' => 'mermaid',
    'renderers' => [
        'mermaid' => MermaidRenderer::class,
        'dbdiagram' => DbDiagramRenderer::class,
    ],
];
