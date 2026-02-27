<?php

namespace PaoloBellini\LaravelEr\Contracts;

interface SchemaRenderer
{
    public function render(array $schema): string;

    public function outputExtension(): string;

    public function wrapOutput(): string;
}
