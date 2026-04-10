<?php

namespace Fr3on\Atlas\Pulse;

use Fr3on\Atlas\Scanners\CommandScanner;
use Fr3on\Atlas\Scanners\EventScanner;
use Fr3on\Atlas\Scanners\JobScanner;
use Fr3on\Atlas\Scanners\RouteScanner;
use Laravel\Pulse\Livewire\Card;
use Livewire\Attributes\Lazy;

#[Lazy]
class AtlasPulseCard extends Card
{
    public function render()
    {
        return view('atlas::livewire.pulse.atlas-card', [
            'routes' => (new RouteScanner)->scan()->count(),
            'commands' => (new CommandScanner)->scan()->count(),
            'events' => (new EventScanner)->scan()->count(),
            'jobs' => (new JobScanner)->scan()->count(),
        ]);
    }
}
