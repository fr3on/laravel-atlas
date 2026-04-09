<?php

namespace Fr3on\Atlas\Scanners;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection;
use ReflectionMethod;
use ReflectionClass;
use Closure;

class RouteScanner
{
    /**
     * Scan the application and return all registered routes with extended metadata.
     */
    public function scan(): Collection
    {
        return collect(Route::getRoutes())
            ->map(function ($route) {
                $inspector = $this->inspectAction($route->getAction('controller'));

                return [
                    'method' => implode('|', $route->methods()),
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'action' => $route->getActionName(),
                    'middleware' => $route->middleware(),
                    'is_vendor' => $this->isVendor($route),
                    'file' => $inspector['file'] ?? null,
                    'line' => $inspector['line'] ?? null,
                    'class' => $inspector['class'] ?? null,
                    'method_name' => $inspector['method'] ?? null,
                ];
            });
    }

    /**
     * Extract file and line information from the controller action.
     */
    protected function inspectAction($action): array
    {
        if (! $action || $action instanceof Closure) {
            return [];
        }

        try {
            if (is_string($action) && str_contains($action, '@')) {
                list($class, $method) = explode('@', $action);
            } elseif (is_array($action)) {
                $class = $action[0];
                $method = $action[1];
            } else {
                return [];
            }

            if (! class_exists($class)) {
                return [];
            }

            $reflection = new ReflectionMethod($class, $method);

            return [
                'file' => $reflection->getFileName(),
                'line' => $reflection->getStartLine(),
                'class' => $class,
                'method' => $method,
            ];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Determine if the route is defined by a vendor package.
     */
    private function isVendor($route): bool
    {
        $action = $route->getAction('controller');
        
        if (! $action) {
            return false;
        }

        if ($action instanceof Closure) {
            return false;
        }

        $class = is_array($action) ? $action[0] : explode('@', $action)[0];

        return str_contains($class, 'vendor') || (new ReflectionClass($class))->getFileName() && str_contains((new ReflectionClass($class))->getFileName(), 'vendor');
    }
}
