<?php

use Fr3on\Atlas\Scanners\CommandScanner;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;

class TestCommand extends Command
{
    protected $signature = 'atlas:test {arg : description} {--opt=default : opt-description}';

    protected $description = 'A test command';

    public function handle()
    {
        //
    }
}

test('it can scan commands', function () {
    // Register the command directly in the application
    app()->make(Kernel::class)->registerCommand(new TestCommand);

    $scanner = new CommandScanner;
    $commands = $scanner->scan();

    $testCommand = $commands->firstWhere('name', 'atlas:test');

    expect($testCommand)->not->toBeNull();
    expect($testCommand['description'])->toBe('A test command');
    expect($testCommand['arguments'])->toHaveCount(1);
    expect($testCommand['arguments'][0]['name'])->toBe('arg');
    expect($testCommand['options'])->toContain([
        'name' => 'opt',
        'description' => 'opt-description',
        'default' => 'default',
        'shortcut' => null,
    ]);
});

test('it can hide framework commands', function () {
    config(['atlas.filters.hide_framework_commands' => true]);

    $scanner = new CommandScanner;
    $commands = $scanner->scan();

    foreach ($commands as $command) {
        expect($command['class'])->not->toStartWith('Illuminate\\');
        expect($command['class'])->not->toStartWith('Symfony\\');
    }
});
