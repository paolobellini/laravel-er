<?php

namespace PaoloBellini\LaravelEr\Contracts;

interface SchemaRenderer
{
    /**
     * @param  array<string, array<string, mixed>>  $schema
     */
    public function render(array $schema): string;

    public function outputExtension(): string;

    public function wrapOutput(): string;
}
