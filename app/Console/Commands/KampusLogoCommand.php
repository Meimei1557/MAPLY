<?php

namespace App\Console\Commands;

use App\Models\Kampus;
use App\Models\KampusLokasi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Mengunduh logo kampus (disimpan sebagai PNG) lalu mengisi kampus_lokasi.logo_path
 * untuk SEMUA lokasi kampus itu, dan mencatatnya juga di database/data/kampus_lokasi.json
 * supaya tidak hilang saat seeder dijalankan ulang.
 *
 *   php artisan kampus:logo                      -> semua kampus yang belum punya logo
 *   php artisan kampus:logo --kode=ukb           -> satu kampus (dari situs resminya)
 *   php artisan kampus:logo --kode=ukb --force   -> timpa logo yang sudah ada
 *   php artisan kampus:logo --kode=upgri-palembang --url=https://situs/logo.png
 *
 * Tanpa unduhan apa pun, perintah ini tetap menyambungkan file public/logos/kampus/{kode}.png
 * yang sudah ada ke semua lokasi kampusnya (memperbaiki UNSRI Indralaya yang kosong).
 */
class KampusLogoCommand extends Command
{
    protected $signature = 'kampus:logo {--kode= : Kode kampus (kolom kampus.kode)} {--url= : URL gambar logo (bersama --kode)} {--force : Timpa logo yang sudah ada}';

    protected $description = 'Unduh logo kampus sebagai PNG ke public/logos/kampus dan sambungkan ke semua lokasinya';

    private const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36';

    /** @var array<string, array{0:string,1:string}> kode kampus => [sumber, url] dari unduhan sesi ini */
    private array $catatan = [];

    public function handle(): int
    {
        if (!function_exists('imagecreatefromstring')) {
            $this->warn('Ekstensi GD PHP belum aktif: gambar non-PNG tidak bisa diubah ke PNG (hanya PNG yang diterima).');
            $this->warn('Aktifkan di XAMPP: php.ini -> hapus titik koma di ";extension=gd", restart Apache.');
        }

        $dir = public_path('logos/kampus');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $kode = $this->option('kode');
        $query = Kampus::query()->orderBy('nama_resmi');
        if ($kode) {
            $query->where('kode', $kode);
        }
        $daftar = $query->get();

        if ($daftar->isEmpty()) {
            $this->error('Kampus tidak ditemukan.');

            return self::FAILURE;
        }

        $berhasil = 0;
        $gagal = [];

        foreach ($daftar as $k) {
            if ($this->option('force') || !$this->fileLogo($k->kode)) {
                $kandidat = [];
                if ($this->option('url') && $kode) {
                    $kandidat[] = (string) $this->option('url');
                } elseif ($k->situs_resmi) {
                    $kandidat = $this->cariKandidat($k->situs_resmi);
                }

                $hasil = null;
                foreach (array_slice($kandidat, 0, 8) as $url) {
                    $hasil = $this->unduh($url);
                    if ($hasil) {
                        break;
                    }
                }

                if ($hasil) {
                    [$isi, $ext, $url] = $hasil;
                    file_put_contents(public_path("logos/kampus/{$k->kode}.{$ext}"), $isi);
                    $this->catatan[$k->kode] = [$this->option('url') ? 'URL manual' : 'Situs resmi (unduh otomatis)', mb_substr($url, 0, 500)];
                    $this->info("OK   {$k->kode} -> logos/kampus/{$k->kode}.{$ext}");
                    $berhasil++;
                    usleep(700000);
                } elseif (!$this->fileLogo($k->kode)) {
                    $gagal[] = [$k->kode, $k->situs_resmi ?: '(tidak ada situs resmi di data)'];
                }
            }

            // Sambungkan file logo (jika ada) ke SEMUA lokasi kampus ini.
            if ($path = $this->fileLogo($k->kode)) {
                [$sumber, $urlSumber] = $this->catatan[$k->kode] ?? ['File lokal', null];
                foreach (KampusLokasi::query()->where('kampus_id', $k->id)->get() as $l) {
                    if ($l->logo_path !== $path || isset($this->catatan[$k->kode])) {
                        $l->logo_path = $path;
                        if (isset($this->catatan[$k->kode])) {
                            $l->logo_sumber = $sumber;
                            $l->logo_url_sumber = $urlSumber;
                        }
                        $l->save();
                    }
                }
            }
        }

        $this->perbaruiJson();

        $this->newLine();
        $this->info("Selesai: {$berhasil} logo baru diunduh.");

        if ($gagal) {
            $this->warn('Belum ada logo (simpan manual sebagai public/logos/kampus/{kode}.png, lalu jalankan lagi perintah ini; atau pakai --url):');
            foreach ($gagal as [$kd, $situs]) {
                $this->line("  - {$kd}  [{$situs}]");
            }
        }

        return self::SUCCESS;
    }

    /** Path relatif (terhadap public/) logo raster kampus bila filenya ada. */
    private function fileLogo(string $kode): ?string
    {
        foreach (['png', 'jpg', 'jpeg', 'webp'] as $ext) {
            if (is_file(public_path("logos/kampus/{$kode}.{$ext}"))) {
                return "logos/kampus/{$kode}.{$ext}";
            }
        }

        return null;
    }

    /** Catat logo di kampus_lokasi.json agar KampusSeeder tidak mengosongkannya lagi. */
    private function perbaruiJson(): void
    {
        $file = database_path('data/kampus_lokasi.json');
        if (!is_file($file)) {
            return;
        }
        $rows = json_decode((string) file_get_contents($file), true);
        if (!is_array($rows)) {
            return;
        }

        $ubah = false;
        foreach ($rows as &$row) {
            $path = $this->fileLogo((string) ($row['kampus_slug'] ?? ''));
            if (!$path) {
                continue;
            }
            $sekarang = $row['logo']['path'] ?? null;
            if ($sekarang === $path && !isset($this->catatan[$row['kampus_slug']])) {
                continue;
            }
            [$sumber, $url] = $this->catatan[$row['kampus_slug']] ?? [$row['logo']['sumber'] ?? 'File lokal', $row['logo']['url_sumber'] ?? null];
            $row['logo'] = ['path' => $path, 'sumber' => $sumber, 'url_sumber' => $url];
            $ubah = true;
        }
        unset($row);

        if ($ubah) {
            file_put_contents($file, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->line('kampus_lokasi.json diperbarui.');
        }
    }

    private function http()
    {
        // verify=false: banyak XAMPP/Windows tanpa bundel sertifikat CA (cURL error 60).
        return Http::withHeaders(['User-Agent' => self::UA, 'Accept' => '*/*'])
            ->withOptions(['verify' => false])
            ->timeout(20);
    }

    /** @return list<string> */
    private function cariKandidat(string $situs): array
    {
        try {
            $res = $this->http()->get($situs);
        } catch (\Throwable $e) {
            $this->line("     gagal membuka {$situs}: ".mb_substr($e->getMessage(), 0, 80));

            return [];
        }
        if (!$res->ok()) {
            return [];
        }

        $base = (string) ($res->effectiveUri() ?? $situs);

        $prev = libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>'.$res->body());
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $imgLogo = $apple = $og = $icon = [];

        foreach ($dom->getElementsByTagName('img') as $img) {
            $src = $img->getAttribute('src') ?: $img->getAttribute('data-src');
            $ciri = strtolower($src.' '.$img->getAttribute('alt').' '.$img->getAttribute('class').' '.$img->getAttribute('id'));
            if ($src && str_contains($ciri, 'logo') && !str_contains($ciri, 'partner') && !str_contains($ciri, 'sponsor')) {
                $imgLogo[] = $src;
            }
        }
        foreach ($dom->getElementsByTagName('link') as $link) {
            $rel = strtolower($link->getAttribute('rel'));
            $href = $link->getAttribute('href');
            if (!$href) {
                continue;
            }
            if (str_contains($rel, 'apple-touch-icon')) {
                $apple[] = $href;
            } elseif (str_contains($rel, 'icon')) {
                $icon[] = $href;
            }
        }
        foreach ($dom->getElementsByTagName('meta') as $meta) {
            $p = strtolower($meta->getAttribute('property') ?: $meta->getAttribute('name'));
            if (in_array($p, ['og:image', 'twitter:image'], true) && $meta->getAttribute('content')) {
                $og[] = $meta->getAttribute('content');
            }
        }

        $semua = array_merge($imgLogo, $apple, $og, $icon);

        return array_values(array_unique(array_filter(array_map(fn ($u) => $this->absolut($u, $base), $semua))));
    }

    private function absolut(string $url, string $base): ?string
    {
        $url = trim($url);
        if ($url === '' || str_starts_with($url, 'data:')) {
            return null;
        }
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }
        $p = parse_url($base);
        if (!$p || empty($p['host'])) {
            return null;
        }
        $skema = $p['scheme'] ?? 'https';
        $origin = $skema.'://'.$p['host'].(isset($p['port']) ? ':'.$p['port'] : '');
        if (str_starts_with($url, '//')) {
            return $skema.':'.$url;
        }
        if (str_starts_with($url, '/')) {
            return $origin.$url;
        }
        $dirPath = isset($p['path']) ? preg_replace('#/[^/]*$#', '/', $p['path']) : '/';

        return $origin.$dirPath.$url;
    }

    /** @return array{0:string,1:string,2:string}|null [isi, ekstensi, url] */
    private function unduh(string $url): ?array
    {
        try {
            $res = $this->http()->get($url);
        } catch (\Throwable) {
            return null;
        }
        if (!$res->ok()) {
            return null;
        }

        $isi = $res->body();
        $len = strlen($isi);
        if ($len < 400 || $len > 3_000_000) {
            return null;
        }

        $info = @getimagesizefromstring($isi); // SVG/ICO tidak lolos di sini (sengaja)
        if (!$info || $info[0] < 48 || $info[1] < 48) {
            return null;
        }

        if ($info[2] === IMAGETYPE_PNG) {
            return [$isi, 'png', $url];
        }

        if (!function_exists('imagecreatefromstring') || !in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_WEBP, IMAGETYPE_GIF], true)) {
            return null;
        }

        $src = @imagecreatefromstring($isi);
        if (!$src) {
            return null;
        }
        $w = imagesx($src);
        $h = imagesy($src);
        $skala = min(1, 512 / max($w, $h));
        $nw = max(1, (int) round($w * $skala));
        $nh = max(1, (int) round($h * $skala));
        $dst = imagecreatetruecolor($nw, $nh);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 255, 255, 255, 127));
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
        ob_start();
        imagepng($dst);
        $png = (string) ob_get_clean();

        return $png !== '' ? [$png, 'png', $url] : null;
    }
}
