<?php

namespace App\Support\AiData;

use InvalidArgumentException;

/**
 * Pengelola seluruh connector sumber (driver pattern).
 *
 * Daftar kelas connector diambil dari config/ai_pipeline.php sehingga penambahan
 * sumber baru cukup dengan menulis 1 kelas + 1 baris konfigurasi.
 */
class SourceConnectorManager
{
    /** @var array<string, SourceConnector> */
    protected array $connectors = [];

    /**
     * @param  SourceConnector[]  $connectors
     */
    public function __construct(array $connectors = [])
    {
        foreach ($connectors as $connector) {
            $this->add($connector);
        }
    }

    /**
     * Bangun manager dari konfigurasi (dipakai AppServiceProvider sebagai singleton).
     */
    public static function fromConfig(): static
    {
        $connectors = [];

        foreach ((array) config('ai_pipeline.connectors', []) as $class) {
            $connector = app($class);

            if ($connector instanceof SourceConnector) {
                $connectors[$connector->key()] = $connector;
            }
        }

        return new static($connectors);
    }

    public function add(SourceConnector $connector): void
    {
        $this->connectors[$connector->key()] = $connector;
    }

    /** @return array<string, SourceConnector> */
    public function all(): array
    {
        return $this->connectors;
    }

    public function get(string $key): ?SourceConnector
    {
        return $this->connectors[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($this->connectors[$key]);
    }

    /** @return string[] */
    public function keys(): array
    {
        return array_keys($this->connectors);
    }

    /**
     * Validasi daftar kunci sumber pilihan admin; minimal 1 sumber harus dipilih.
     *
     * @param  string[]  $keys
     * @return string[]
     *
     * @throws InvalidArgumentException bila ada kunci tidak dikenal atau kosong.
     */
    public function filterSelected(array $keys): array
    {
        $selected = [];

        foreach ($keys as $key) {
            $key = trim((string) $key);

            if ($key === '') {
                continue;
            }

            if (!$this->has($key)) {
                throw new InvalidArgumentException("Sumber \"{$key}\" tidak dikenal.");
            }

            $selected[] = $key;
        }

        if (count($selected) === 0) {
            throw new InvalidArgumentException('Pilih minimal satu sumber data.');
        }

        return array_values(array_unique($selected));
    }
}
