<?php

namespace Fr3on\Atlas\Scanners;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use ReflectionMethod;

class ModelScanner
{
    /**
     * Scan app/Models and return metadata about Eloquent models and their relations.
     */
    public function scan(): Collection
    {
        $path = app_path('Models');

        if (! File::exists($path)) {
            // Check app/ if app/Models doesn't exist (older Laravel)
            $path = app_path();
        }

        return collect(File::allFiles($path))
            ->filter(fn ($file) => $file->getExtension() === 'php')
            ->map(function ($file) {
                $class = $this->getClassFromFile($file);

                if (! $class || ! class_exists($class)) {
                    return null;
                }

                $reflection = new ReflectionClass($class);

                if ($reflection->isAbstract() || ! $reflection->isSubclassOf('Illuminate\Database\Eloquent\Model')) {
                    return null;
                }

                $model = new $class;

                return [
                    'name' => $reflection->getShortName(),
                    'class' => $class,
                    'table' => $model->getTable(),
                    'file' => $file->getRealPath(),
                    'hidden' => $model->getHidden(),
                    'fillable' => $model->getFillable(),
                    'relations' => $this->getRelationships($reflection),
                ];
            })
            ->filter()
            ->values();
    }

    protected function getClassFromFile($file): ?string
    {
        $content = file_get_contents($file->getRealPath());
        if (preg_match('/namespace\s+(.+);/', $content, $matches)) {
            return $matches[1].'\\'.$file->getBasename('.php');
        }

        return null;
    }

    protected function getRelationships(ReflectionClass $reflection): array
    {
        $relations = [];
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            if ($method->class !== $reflection->getName() || $method->getNumberOfParameters() > 0) {
                continue;
            }

            try {
                $returnType = $method->getReturnType();
                if ($returnType) {
                    $returnClass = $returnType->getName();
                    if (str_contains($returnClass, 'Illuminate\Database\Eloquent\Relations')) {
                        $relations[] = [
                            'name' => $method->getName(),
                            'type' => class_basename($returnClass),
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Skip
            }
        }

        return $relations;
    }
}
