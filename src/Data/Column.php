<?php

declare(strict_types=1);

namespace PaoloBellini\LaravelEr\Data;

final readonly class Column
{
    public function __construct(
        public string $name,
        public string $type,
        public bool $nullable,
        public ?string $default,
    ) {}
}
