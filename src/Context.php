<?php

namespace PaoloBellini\LaravelEr;

use PaoloBellini\LaravelEr\Contracts\SchemaRenderer;

final class Context
{
    private SchemaRenderer $strategy;

    public function __construct(private readonly SchemaReader $reader) {}

    public function setStrategy(SchemaRenderer $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function executeStrategy(): string
    {
        $schema = $this->reader->read();

        return $this->strategy->render($schema);
    }
}
