<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Data;

final readonly class Index
{
    /**
     * @param  list<string>  $columns
     */
    public function __construct(
        public array $columns,
        public bool $primary = false,
        public bool $unique = false,
    ) {}
}
