<?php

namespace Fr3on\Atlas\Scanners;

use Illuminate\Support\Collection;

class ConfigScanner
{
    protected array $blacklist = [
        'key', 'secret', 'password', 'token', 'crypt', 'salt', 'private', 'auth', 'database', 'dsn',
    ];

    /**
     * Scan application configuration and return a sanitized tree.
     */
    public function scan(): Collection
    {
        $config = config()->all();
        $flattened = $this->flatten($config);

        return collect($flattened)->map(function ($value, $key) {
            return [
                'key' => $key,
                'value' => $this->sanitize($key, $value),
                'group' => explode('.', $key)[0],
            ];
        })->values();
    }

    protected function flatten(array $array, $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && ! empty($value) && ! array_is_list($value)) {
                $result = array_merge($result, $this->flatten($value, $prefix.$key.'.'));
            } else {
                $result[$prefix.$key] = $value;
            }
        }

        return $result;
    }

    protected function sanitize(string $key, $value)
    {
        if (is_null($value)) {
            return 'null';
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        foreach ($this->blacklist as $term) {
            if (str_contains(strtolower($key), $term)) {
                return '********';
            }
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return (string) $value;
    }
}
