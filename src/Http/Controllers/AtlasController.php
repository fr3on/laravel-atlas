<?php

namespace Fr3on\Atlas\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Fr3on\Atlas\Scanners\RouteScanner;
use Fr3on\Atlas\Scanners\CommandScanner;
use Fr3on\Atlas\Scanners\ScheduleScanner;
use Fr3on\Atlas\Scanners\EventScanner;
use Fr3on\Atlas\Scanners\ModelScanner;
use Fr3on\Atlas\Scanners\MigrationScanner;
use Fr3on\Atlas\Scanners\ConfigScanner;
use Fr3on\Atlas\Scanners\PolicyScanner;
use Fr3on\Atlas\Traits\GeneratesMarkdown;

class AtlasController extends Controller
{
    use GeneratesMarkdown;

    public function index()
    {
        return redirect()->route('atlas.show', ['panel' => 'routes']);
    }

    public function show(Request $request, $panel)
    {
        $panels = config('atlas.panels', [
            'routes' => true,
            'models' => true,
            'commands' => true,
            'migrations' => true,
            'events' => true,
            'schedule' => true,
            'config' => true,
            'policies' => true,
        ]);

        if (! array_key_exists($panel, $panels) || ! $panels[$panel]) {
            abort(404);
        }

        $search = $request->get('q');
        $data = $this->getPanelData($panel);

        if ($search) {
            $data = $this->filterData($data, $panel, $search);
        }

        $stats = $this->getStats();
        $paginatedData = $this->paginate($data, $request);

        return view('atlas::layout', [
            'panel' => $panel,
            'data' => $paginatedData,
            'total_items' => $data->count(),
            'panels' => collect($panels)->filter()->keys(),
            'stats' => $stats,
            'search' => $search,
        ]);
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'json');
        
        $data = collect([
            'routes' => (new RouteScanner())->scan(),
            'models' => (new ModelScanner())->scan(),
            'commands' => (new CommandScanner())->scan(),
            'migrations' => (new MigrationScanner())->scan(),
            'events' => (new EventScanner())->scan(),
            'schedule' => (new ScheduleScanner())->scan(),
            'config' => (new ConfigScanner())->scan(),
            'policies' => (new PolicyScanner())->scan(),
        ]);

        if ($format === 'json') {
            return response()->json($data);
        }

        return response($this->generateMarkdown($data), 200, [
            'Content-Type' => 'text/markdown',
            'Content-Disposition' => 'attachment; filename="atlas-export.md"',
        ]);
    }

    protected function getPanelData($panel): Collection
    {
        return match ($panel) {
            'routes' => (new RouteScanner())->scan(),
            'models' => (new ModelScanner())->scan(),
            'commands' => (new CommandScanner())->scan(),
            'migrations' => (new MigrationScanner())->scan(),
            'events' => (new EventScanner())->scan(),
            'schedule' => (new ScheduleScanner())->scan(),
            'config' => (new ConfigScanner())->scan(),
            'policies' => (new PolicyScanner())->scan(),
            default => collect(),
        };
    }

    protected function filterData(Collection $data, $panel, $search): Collection
    {
        $search = strtolower($search);

        return $data->filter(function ($item) use ($panel, $search) {
            $stringToSearch = match ($panel) {
                'routes' => ($item['uri'] ?? '') . ' ' . ($item['name'] ?? '') . ' ' . ($item['action'] ?? ''),
                'models' => ($item['name'] ?? '') . ' ' . ($item['table'] ?? ''),
                'commands' => ($item['name'] ?? '') . ' ' . ($item['description'] ?? ''),
                'migrations' => ($item['title'] ?? '') . ' ' . ($item['name'] ?? ''),
                'schedule' => ($item['command'] ?? '') . ' ' . ($item['description'] ?? '') . ' ' . ($item['expression'] ?? ''),
                'events' => ($item['event'] ?? '') . ' ' . implode(' ', collect($item['listeners'] ?? [])->pluck('name')->toArray()),
                'config' => ($item['key'] ?? '') . ' ' . ($item['value'] ?? ''),
                'policies' => ($item['model'] ?? '') . ' ' . ($item['class'] ?? ''),
                default => '',
            };

            return str_contains(strtolower($stringToSearch), $search);
        });
    }

    protected function paginate(Collection $items, Request $request)
    {
        $perPage = 50;
        $page = $request->get('page', 1);
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(
            $items->slice($offset, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    protected function getStats(): array
    {
        $stats = [];
        $res = ['routes', 'models', 'commands', 'migrations', 'events', 'schedule', 'config', 'policies'];
        
        foreach($res as $r) {
            $stats[$r . '_count'] = $this->getPanelData($r)->count();
        }

        // Keep existing specifics
        $routes = (new RouteScanner())->scan();
        $stats['throttled_count'] = $routes->filter(fn($r) => collect($r['middleware'] ?? [])->contains(fn($m) => str_contains($m, 'throttle')))->count();
        $stats['public_count'] = $routes->filter(fn($r) => ! collect($r['middleware'] ?? [])->contains(fn($m) => str_contains($m, 'auth') || str_contains($m, 'sanctum')))->count();

        return $stats;
    }
}
