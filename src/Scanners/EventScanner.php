<?php

namespace Fr3on\Atlas\Scanners;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use ReflectionClass;

class EventScanner
{
    /**
     * Scan the application and return all registered events and their listeners with file paths.
     */
    public function scan(): Collection
    {
        $dispatcher = Event::getFacadeRoot();

        $reflection = new ReflectionClass($dispatcher);
        $property = $reflection->getProperty('listeners');
        $property->setAccessible(true);
        $listeners = $property->getValue($dispatcher);

        return collect($listeners)
            ->map(function ($eventListeners, $event) {
                return [
                    'event' => $event,
                    'listeners' => collect($eventListeners)->map(function ($listener) {
                        return $this->resolveListenerDetails($listener);
                    })->toArray(),
                ];
            })
            ->values();
    }

    private function resolveListenerDetails($listener): array
    {
        if ($listener instanceof Closure) {
            return ['name' => 'Closure', 'file' => 'Closure'];
        }

        if (is_string($listener)) {
            $class = $listener;
            $method = 'handle';
        } elseif (is_array($listener)) {
            $class = is_object($listener[0]) ? get_class($listener[0]) : $listener[0];
            $method = $listener[1];
        } else {
            return ['name' => 'Unknown', 'file' => null];
        }

        try {
            $ref = new ReflectionClass($class);

            return [
                'name' => $class.'@'.$method,
                'class' => $class,
                'method' => $method,
                'file' => $ref->getFileName(),
                'line' => $ref->hasMethod($method) ? $ref->getMethod($method)->getStartLine() : $ref->getStartLine(),
            ];
        } catch (\Exception $e) {
            return ['name' => $class.'@'.$method, 'file' => null];
        }
    }
}
