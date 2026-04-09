<?php

use Fr3on\Atlas\Scanners\MigrationScanner;
use Illuminate\Support\Facades\File;

test('it can scan migrations', function () {
    $migrationsPath = database_path('migrations');
    if (!File::exists($migrationsPath)) {
        File::makeDirectory($migrationsPath, 0755, true);
    }

    $migrationName = '2026_01_01_000000_create_test_table';
    File::put($migrationsPath . '/' . $migrationName . '.php', '<?php');

    $scanner = new MigrationScanner;
    $migrations = $scanner->scan();

    $migration = $migrations->firstWhere('name', $migrationName);

    expect($migration)->not->toBeNull();
    expect($migration['date'])->toBe('2026-01-01');
    expect($migration['title'])->toBe('create test table');
    expect($migration['status'])->toBe('pending'); // Since DB table doesn't exist

    // Cleanup
    File::delete($migrationsPath . '/' . $migrationName . '.php');
});
