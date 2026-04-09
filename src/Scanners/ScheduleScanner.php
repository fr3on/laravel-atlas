<?php

namespace Fr3on\Atlas\Scanners;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;

class ScheduleScanner
{
    /**
     * Scan the application and return all scheduled tasks with detailed timing metadata.
     */
    public function scan(): Collection
    {
        $schedule = app(Schedule::class);

        return collect($schedule->events())
            ->map(function ($event) {
                return [
                    'command' => $event->command,
                    'description' => $event->description,
                    'expression' => $event->expression,
                    'timezone' => $event->timezone,
                    'on_one_server' => $event->onOneServer,
                    'without_overlapping' => $event->withoutOverlapping,
                    'run_in_background' => $event->runInBackground,
                    'even_in_maintenance_mode' => $event->evenInMaintenanceMode,
                    'metadata' => [
                        'raw_expression' => $event->expression,
                        'is_closure' => str_contains($event->command, 'Closure'),
                    ],
                ];
            });
    }
}
