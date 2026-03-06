<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Data;

final readonly class Table
{
    /**
     * @param  list<Column>  $columns
     * @param  list<ForeignKey>  $foreignKeys
     * @param  list<Index>  $indexes
     */
    public function __construct(
        public string $name,
        public array $columns,
        public array $foreignKeys,
        public array $indexes,
    ) {}
}
