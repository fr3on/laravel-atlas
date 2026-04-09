<?php

use Fr3on\Atlas\Scanners\PolicyScanner;
use Illuminate\Support\Facades\Gate;

class TestModel {}
class TestPolicy {
    public function viewAny() {}
    public function view() {}
}

test('it can scan policies', function () {
    Gate::policy(TestModel::class, TestPolicy::class);

    $scanner = new PolicyScanner;
    $policies = $scanner->scan();

    $policy = $policies->firstWhere('model', TestModel::class);

    expect($policy)->not->toBeNull();
    expect($policy['class'])->toBe(TestPolicy::class);
    expect($policy['methods'])->toContain('viewAny', 'view');
});
