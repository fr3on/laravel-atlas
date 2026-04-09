<?php

namespace Fr3on\Atlas\Scanners;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use ReflectionClass;

class PolicyScanner
{
    /**
     * Scan the application and return all policies and gate abilities.
     */
    public function scan(): Collection
    {
        $policies = Gate::policies();
        $results = [];

        // 1. Explicitly registered policies
        foreach ($policies as $model => $policy) {
            $results[] = [
                'type' => 'policy',
                'model' => $model,
                'class' => $policy,
                'methods' => $this->getPolicyMethods($policy),
            ];
        }

        // 2. Scan app/Policies for auto-discovered ones
        $path = app_path('Policies');
        if (File::exists($path)) {
            foreach (File::files($path) as $file) {
                $class = $this->getClassFromFile($file);
                if ($class && ! collect($results)->contains('class', $class)) {
                    $results[] = [
                        'type' => 'policy',
                        'model' => str_replace('Policy', '', class_basename($class)),
                        'class' => $class,
                        'methods' => $this->getPolicyMethods($class),
                    ];
                }
            }
        }

        return collect($results)->values();
    }

    protected function getPolicyMethods(string $class): array
    {
        try {
            $ref = new ReflectionClass($class);

            return collect($ref->getMethods(\ReflectionMethod::IS_PUBLIC))
                ->filter(fn ($m) => ! $m->isConstructor())
                ->pluck('name')
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getClassFromFile($file): ?string
    {
        $content = file_get_contents($file->getRealPath());
        if (preg_match('/namespace\s+(.+);/', $content, $matches)) {
            return $matches[1].'\\'.$file->getBasename('.php');
        }

        return null;
    }
}
