<?php

use Fr3on\Atlas\Scanners\ScheduleScanner;
use Illuminate\Console\Scheduling\Schedule;

test('it can scan scheduled tasks', function () {
    $schedule = app(Schedule::class);
    $schedule->command('inspire')->hourly();

    $scanner = new ScheduleScanner;
    $tasks = $scanner->scan();

    $inspire = $tasks->first(fn ($t) => str_contains($t['command'], 'inspire'));

    expect($inspire)->not->toBeNull();
    expect($inspire['expression'])->toBe('0 * * * *');
});
