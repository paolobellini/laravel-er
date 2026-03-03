<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Data;

final readonly class Schema
{
    /**
     * @param  list<Table>  $tables
     */
    public function __construct(
        public array $tables,
    ) {}
}
