<?php

namespace Fr3on\Atlas\Commands;

use Fr3on\Atlas\Scanners\CommandScanner;
use Fr3on\Atlas\Scanners\ConfigScanner;
use Fr3on\Atlas\Scanners\EventScanner;
use Fr3on\Atlas\Scanners\JobScanner;
use Fr3on\Atlas\Scanners\MigrationScanner;
use Fr3on\Atlas\Scanners\ModelScanner;
use Fr3on\Atlas\Scanners\PolicyScanner;
use Fr3on\Atlas\Scanners\RouteScanner;
use Fr3on\Atlas\Scanners\ScheduleScanner;
use Fr3on\Atlas\Traits\GeneratesMarkdown;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class AtlasExportCommand extends Command
{
    use GeneratesMarkdown;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atlas:export 
                            {--panel=all : The panel to export (routes, commands, schedule, events, jobs, all)}
                            {--format=markdown : The format to export in (markdown, json)}
                            {--output= : The file path to save the export to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export application map to Markdown or JSON';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $panel = $this->option('panel');
        $format = $this->option('format');
        $output = $this->option('output');

        $data = $this->gatherData($panel);

        $content = $format === 'json'
            ? $data->toJson(JSON_PRETTY_PRINT)
            : $this->generateMarkdown($data);

        if ($output) {
            file_put_contents($output, $content);
            $this->info("Exported to {$output}");
        } else {
            $this->line($content);
        }

        return 0;
    }

    protected function gatherData(string $panel): Collection
    {
        if ($panel === 'all') {
            return collect([
                'routes' => (new RouteScanner)->scan(),
                'models' => (new ModelScanner)->scan(),
                'commands' => (new CommandScanner)->scan(),
                'migrations' => (new MigrationScanner)->scan(),
                'schedule' => (new ScheduleScanner)->scan(),
                'events' => (new EventScanner)->scan(),
                'jobs' => (new JobScanner)->scan(),
                'config' => (new ConfigScanner)->scan(),
                'policies' => (new PolicyScanner)->scan(),
            ]);
        }

        return match ($panel) {
            'routes' => collect(['routes' => (new RouteScanner)->scan()]),
            'models' => collect(['models' => (new ModelScanner)->scan()]),
            'commands' => collect(['commands' => (new CommandScanner)->scan()]),
            'migrations' => collect(['migrations' => (new MigrationScanner)->scan()]),
            'schedule' => collect(['schedule' => (new ScheduleScanner)->scan()]),
            'events' => collect(['events' => (new EventScanner)->scan()]),
            'jobs' => collect(['jobs' => (new JobScanner)->scan()]),
            'config' => collect(['config' => (new ConfigScanner)->scan()]),
            'policies' => collect(['policies' => (new PolicyScanner)->scan()]),
            default => collect([]),
        };
    }

    // Logic moved to GeneratesMarkdown trait
}
