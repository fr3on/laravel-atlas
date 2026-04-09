<?php

use Fr3on\Atlas\Scanners\RouteScanner;
use Illuminate\Support\Facades\Route;

test('it can scan routes', function () {
    Route::get('/test', fn() => 'ok')->name('test.route');
    
    $scanner = new RouteScanner();
    $routes = $scanner->scan();

    expect($routes)->not->toBeEmpty();
    expect($routes->firstWhere('uri', 'test'))->not->toBeNull();
});
