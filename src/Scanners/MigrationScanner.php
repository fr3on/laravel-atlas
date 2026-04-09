<?php

namespace Fr3on\Atlas\Scanners;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MigrationScanner
{
    /**
     * Scan database/migrations and return a detailed timeline.
     */
    public function scan(): Collection
    {
        $path = database_path('migrations');

        if (! File::exists($path)) {
            return collect();
        }

        $ranMigrations = $this->getRanMigrations();

        return collect(File::files($path))
            ->map(function ($file) use ($ranMigrations) {
                $name = $file->getBasename('.php');
                $parts = explode('_', $name, 5);

                return [
                    'name' => $name,
                    'date' => isset($parts[0]) ? "{$parts[0]}-{$parts[1]}-{$parts[2]}" : 'Unknown',
                    'title' => isset($parts[4]) ? str_replace('_', ' ', $parts[4]) : $name,
                    'file' => $file->getRealPath(),
                    'status' => in_array($name, $ranMigrations) ? 'applied' : 'pending',
                ];
            })
            ->sortByDesc('name')
            ->values();
    }

    protected function getRanMigrations(): array
    {
        try {
            if (! DB::connection()->getPdo()) {
                return [];
            }

            return DB::table('migrations')->pluck('migration')->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
