<?php

namespace Fr3on\Atlas\Scanners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class JobScanner
{
    /**
     * Scan the app directory for classes implementing ShouldQueue.
     */
    public function scan(): Collection
    {
        $appPath = app_path();
        if (! is_dir($appPath)) {
            return collect();
        }

        $files = File::allFiles($appPath);
        $jobs = collect();

        foreach ($files as $file) {
            $content = file_get_contents($file->getRealPath());

            // Quick check for ShouldQueue before doing expensive Reflection
            if (! str_contains($content, 'ShouldQueue')) {
                continue;
            }

            $className = $this->extractClassName($file->getRealPath());
            if (! $className || ! class_exists($className)) {
                continue;
            }

            $reflection = new ReflectionClass($className);
            if ($reflection->isInstantiable() && $reflection->implementsInterface(ShouldQueue::class)) {
                $jobs->push([
                    'name' => $reflection->getShortName(),
                    'class' => $className,
                    'queue' => $this->getProperty($reflection, 'queue') ?? 'default',
                    'connection' => $this->getProperty($reflection, 'connection') ?? 'default',
                    'tries' => $this->getProperty($reflection, 'tries') ?? 'default',
                    'timeout' => $this->getProperty($reflection, 'timeout') ?? 'default',
                    'file' => $file->getRelativePathname(),
                ]);
            }
        }

        return $jobs->values();
    }

    /**
     * Get a property value from a class or its instance.
     */
    protected function getProperty(ReflectionClass $reflection, string $name): mixed
    {
        if ($reflection->hasProperty($name)) {
            $prop = $reflection->getProperty($name);
            $prop->setAccessible(true);

            // Try to get default value from property
            $defaults = $reflection->getDefaultProperties();

            return $defaults[$name] ?? null;
        }

        return null;
    }

    /**
     * Poor man's PSR-4 class resolver from file path.
     */
    protected function extractClassName(string $path): ?string
    {
        $path = str_replace(app_path(), 'App', $path);
        $path = str_replace('.php', '', $path);

        return str_replace('/', '\\', $path);
    }
}
