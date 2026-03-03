<?php

use PaoloBellini\LaravelEr\Support\ColumnAttributes;

it('returns true when column has a unique index', function (): void {
    $indexes = [
        ['name' => 'users_email_unique', 'columns' => ['email'], 'unique' => true, 'primary' => false],
    ];

    expect(ColumnAttributes::isUnique('email', $indexes))->toBeTrue();
});

it('returns false when column has no unique index', function (): void {
    $indexes = [
        ['name' => 'users_name_index', 'columns' => ['name'], 'unique' => false, 'primary' => false],
    ];

    expect(ColumnAttributes::isUnique('name', $indexes))->toBeFalse();
});

it('returns the foreign table for a foreign key column', function (): void {
    $foreignKeys = [
        ['columns' => ['user_id'], 'foreign_table' => 'users', 'foreign_columns' => ['id']],
    ];

    expect(ColumnAttributes::getForeignTable('user_id', $foreignKeys))->toBe('users');
});

it('returns null when column is not a foreign key', function (): void {
    $foreignKeys = [
        ['columns' => ['user_id'], 'foreign_table' => 'users', 'foreign_columns' => ['id']],
    ];

    expect(ColumnAttributes::getForeignTable('name', $foreignKeys))->toBeNull();
});

it('returns false for isNullable when column is not found', function (): void {
    $columns = [
        ['name' => 'id', 'nullable' => false],
    ];

    expect(ColumnAttributes::isNullable('nonexistent', $columns))->toBeFalse();
});
