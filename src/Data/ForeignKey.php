<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Data;

final readonly class ForeignKey
{
    /**
     * @param  list<string>  $columns
     */
    public function __construct(
        public array $columns,
        public string $foreignTable,
    ) {}
}
