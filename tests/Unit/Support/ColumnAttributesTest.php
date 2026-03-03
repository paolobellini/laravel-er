<?php

use PaoloBellini\LaravelEr\Data\Column;
use PaoloBellini\LaravelEr\Data\ForeignKey;
use PaoloBellini\LaravelEr\Data\Index;
use PaoloBellini\LaravelEr\Support\ColumnAttributes;

it('returns true when column has a unique index', function (): void {
    $indexes = [
        new Index(columns: ['email'], unique: true),
    ];

    expect(ColumnAttributes::isUnique('email', $indexes))->toBeTrue();
});

it('returns false when column has no unique index', function (): void {
    $indexes = [
        new Index(columns: ['name']),
    ];

    expect(ColumnAttributes::isUnique('name', $indexes))->toBeFalse();
});

it('returns the foreign table for a foreign key column', function (): void {
    $foreignKeys = [
        new ForeignKey(columns: ['user_id'], foreignTable: 'users'),
    ];

    expect(ColumnAttributes::getForeignTable('user_id', $foreignKeys))->toBe('users');
});

it('returns null when column is not a foreign key', function (): void {
    $foreignKeys = [
        new ForeignKey(columns: ['user_id'], foreignTable: 'users'),
    ];

    expect(ColumnAttributes::getForeignTable('name', $foreignKeys))->toBeNull();
});

it('returns true for hasDefault when column has a default value', function (): void {
    $column = new Column('status', 'string', false, 'active');

    expect(ColumnAttributes::hasDefault($column))->toBeTrue();
});

it('returns false for hasDefault when column default is null', function (): void {
    $column = new Column('name', 'string', false, null);

    expect(ColumnAttributes::hasDefault($column))->toBeFalse();
});

it('returns false for isNullable when column is not found', function (): void {
    $columns = [
        new Column('id', 'integer', false, null),
    ];

    expect(ColumnAttributes::isNullable('nonexistent', $columns))->toBeFalse();
});
