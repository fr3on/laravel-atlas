<?php

namespace Fr3on\Atlas\Scanners;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use ReflectionClass;

class CommandScanner
{
    /**
     * Scan the application and return all registered Artisan commands with details.
     */
    public function scan(): Collection
    {
        $hideFramework = config('atlas.filters.hide_framework_commands', true);

        return collect(Artisan::all())
            ->reject(function ($command) use ($hideFramework) {
                if (! $hideFramework) {
                    return false;
                }

                $class = get_class($command);

                return str_starts_with($class, 'Illuminate\\') || str_starts_with($class, 'Symfony\\');
            })
            ->map(function ($command) {
                $reflection = new ReflectionClass($command);
                $definition = $command->getDefinition();

                return [
                    'name' => $command->getName(),
                    'description' => $command->getDescription(),
                    'class' => get_class($command),
                    'file' => $reflection->getFileName(),
                    'line' => $reflection->getStartLine(),
                    'arguments' => collect($definition->getArguments())->map(fn ($a) => [
                        'name' => $a->getName(),
                        'description' => $a->getDescription(),
                        'default' => $a->getDefault(),
                        'required' => $a->isRequired(),
                    ])->values()->toArray(),
                    'options' => collect($definition->getOptions())->map(fn ($o) => [
                        'name' => $o->getName(),
                        'description' => $o->getDescription(),
                        'default' => $o->getDefault(),
                        'shortcut' => $o->getShortcut(),
                    ])->values()->toArray(),
                ];
            })
            ->values();
    }
}
