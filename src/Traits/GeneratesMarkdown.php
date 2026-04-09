<?php

namespace Fr3on\Atlas\Traits;

use Illuminate\Support\Collection;

trait GeneratesMarkdown
{
    /**
     * Generate Markdown documentation from the gathered data.
     */
    protected function generateMarkdown(Collection $data): string
    {
        $md = "# Laravel Atlas Export\n\n";
        $md .= "Generated on: " . now()->toDateTimeString() . "\n\n";

        // If the collection doesn't have named panels, wrap it.
        $panels = isset($data['routes']) || isset($data['commands']) 
            ? $data 
            : collect(['items' => $data]);

        foreach ($panels as $panelName => $items) {
            if ($panelName !== 'items') {
                $md .= "## " . ucfirst($panelName) . "\n\n";
            }
            
            // Logic to determine type if not named
            $type = $panelName;
            if ($panelName === 'items' && $items->isNotEmpty()) {
                $first = $items->first();
                if (isset($first['uri'])) $type = 'routes';
                elseif (isset($first['signature']) || isset($first['name'])) $type = 'commands';
            }

            if ($type === 'routes') {
                $md .= "| Method | URI | Name | Action |\n";
                $md .= "| :--- | :--- | :--- | :--- |\n";
                foreach ($items as $item) {
                    $md .= "| {$item['method']} | `{$item['uri']}` | {$item['name']} | `{$item['action']}` |\n";
                }
            } elseif ($type === 'models') {
                $md .= "| Model | Table | Relations |\n";
                $md .= "| :--- | :--- | :--- |\n";
                foreach ($items as $item) {
                    $rels = collect($item['relations'])->map(fn($r) => "{$r['name']}({$r['type']})")->implode(', ');
                    $md .= "| `{$item['name']}` | `{$item['table']}` | {$rels} |\n";
                }
            } elseif ($type === 'commands') {
                $md .= "| Command | Description |\n";
                $md .= "| :--- | :--- |\n";
                foreach ($items as $item) {
                    $md .= "| `{$item['name']}` | {$item['description']} |\n";
                }
            } elseif ($type === 'migrations') {
                $md .= "| Migration | Status | Date |\n";
                $md .= "| :--- | :--- | :--- |\n";
                foreach ($items as $item) {
                    $md .= "| `{$item['title']}` | {$item['status']} | {$item['date']} |\n";
                }
            } elseif ($type === 'events') {
                $md .= "| Event | Listeners |\n";
                $md .= "| :--- | :--- |\n";
                foreach ($items as $item) {
                    $listeners = collect($item['listeners'] ?? [])->pluck('name')->implode('<br>');
                    $md .= "| `{$item['event']}` | {$listeners} |\n";
                }
            } elseif ($type === 'schedule') {
                $md .= "| Internal | Expression | Description |\n";
                $md .= "| :--- | :--- | :--- |\n";
                foreach ($items as $item) {
                    $md .= "| `{$item['command']}` | `{$item['expression']}` | {$item['description']} |\n";
                }
            } elseif ($type === 'config') {
                $md .= "| Key | Value |\n";
                $md .= "| :--- | :--- |\n";
                foreach ($items as $item) {
                    $md .= "| `{$item['key']}` | {$item['value']} |\n";
                }
            } elseif ($type === 'policies') {
                $md .= "| Model | Policy Class | Methods |\n";
                $md .= "| :--- | :--- | :--- |\n";
                foreach ($items as $item) {
                    $methods = implode(', ', $item['methods']);
                    $md .= "| **{$item['model']}** | `{$item['class']}` | {$methods} |\n";
                }
            }
            
            $md .= "\n";
        }

        return $md;
    }
}
