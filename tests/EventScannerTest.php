<?php

use Fr3on\Atlas\Scanners\EventScanner;
use Illuminate\Support\Facades\Event;

class TestEvent {}
class TestListener
{
    public function handle(TestEvent $event) {}
}

test('it can scan events and listeners', function () {
    Event::listen(TestEvent::class, TestListener::class);
    Event::listen('custom.event', function () {});

    $scanner = new EventScanner;
    $events = $scanner->scan();

    $testEvent = $events->firstWhere('event', TestEvent::class);
    $customEvent = $events->firstWhere('event', 'custom.event');

    expect($testEvent)->not->toBeNull();
    expect($testEvent['listeners'])->toHaveCount(1);
    expect($testEvent['listeners'][0]['class'])->toBe(TestListener::class);

    expect($customEvent)->not->toBeNull();
    expect($customEvent['listeners'])->toHaveCount(1);
    expect($customEvent['listeners'][0]['name'])->toBe('Closure');
});
