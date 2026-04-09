<?php

use Fr3on\Atlas\Scanners\RouteScanner;
use Illuminate\Support\Facades\Route;

test('it can scan routes', function () {
    Route::get('/test', fn () => 'ok')->name('test.route')->middleware('web');
    Route::post('/api/data', fn () => 'ok')->name('api.data');
    
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', fn () => 'ok')->name('dashboard');
    });

    $scanner = new RouteScanner;
    $routes = $scanner->scan();

    expect($routes)->not->toBeEmpty();
    
    $testRoute = $routes->firstWhere('uri', 'test');
    expect($testRoute)->not->toBeNull();
    expect($testRoute['method'])->toContain('GET');
    expect($testRoute['middleware'])->toContain('web');

    $apiRoute = $routes->firstWhere('uri', 'api/data');
    expect($apiRoute)->not->toBeNull();
    expect($apiRoute['method'])->toContain('POST');

    $dashboardRoute = $routes->firstWhere('uri', 'dashboard');
    expect($dashboardRoute)->not->toBeNull();
    expect($dashboardRoute['middleware'])->toContain('auth', 'verified');
});
