<?php

namespace PaoloBellini\LaravelEr\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use PaoloBellini\LaravelEr\LaravelErServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelErServiceProvider::class,
        ];
    }
}
