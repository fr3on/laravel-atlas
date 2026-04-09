<?php

use Fr3on\Atlas\Scanners\ConfigScanner;

test('it can scan and sanitize config', function () {
    config([
        'atlas_test.normal' => 'value',
        'atlas_test.secret_key' => 'password123',
        'atlas_test.array' => ['item1', 'item2'],
        'atlas_test.null' => null,
        'atlas_test.bool' => true,
    ]);

    $scanner = new ConfigScanner;
    $config = $scanner->scan();

    $normal = $config->firstWhere('key', 'atlas_test.normal');
    $secret = $config->firstWhere('key', 'atlas_test.secret_key');
    $array = $config->firstWhere('key', 'atlas_test.array');
    $null = $config->firstWhere('key', 'atlas_test.null');
    $bool = $config->firstWhere('key', 'atlas_test.bool');

    expect($normal['value'])->toBe('value');
    expect($secret['value'])->toBe('********');
    expect($array['value'])->toBe('["item1","item2"]');
    expect($null['value'])->toBe('null');
    expect($bool['value'])->toBe('true');
});
