<?php

namespace App\Console\Commands;

use App\Models\Kos;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Mengambil foto utama (og:image) dari halaman sumber_url milik kos itu sendiri.
 * Halaman yang dipakai banyak kos sekaligus (mis. artikel daftar kos) dilewati,
 * karena fotonya bukan foto kos tersebut.
 *
 *   php artisan kos:foto-sumber            -> semua kos tanpa foto
 *   php artisan kos:foto-sumber --dry      -> hanya tampilkan hasilnya, tidak menyimpan
 */
class KosFotoSumberCommand extends Command
{
    protected $signature = 'kos:foto-sumber {--dry : Jangan simpan, hanya tampilkan} {--force : Timpa foto yang sudah ada}';

    protected $description = 'Ambil foto kos dari halaman listing aslinya (sumber_url) bila halaman itu khusus untuk kos tersebut';

    public function handle(): int
    {
        $pakai = Kos::query()->whereNotNull('sumber_url')->where('sumber_url', '!=', '')
            ->get(['id', 'nama_kos', 'foto', 'sumber_url'])
            ->groupBy('sumber_url');

        $ok = 0;
        $gagal = 0;
        $lewat = 0;

        foreach ($pakai as $url => $daftar) {
            if ($daftar->count() !== 1) {
                $lewat += $daftar->count(); // halaman bersama banyak kos

                continue;
            }
            $kos = $daftar->first();
            if ($kos->foto && !$this->option('force')) {
                continue;
            }

            $foto = $this->ambilOgImage($url);
            if (!$foto) {
                $this->line("-    {$kos->nama_kos}: tidak ada foto di {$url}");
                $gagal++;

                continue;
            }

            $this->info("OK   {$kos->nama_kos} -> {$foto}");
            if (!$this->option('dry')) {
                Kos::whereKey($kos->id)->update(['foto' => mb_substr($foto, 0, 500)]);
            }
            $ok++;
            usleep(800000);
        }

        $this->newLine();
        $this->info("Foto terpasang: {$ok}. Gagal: {$gagal}. Dilewati (halaman bersama): {$lewat}.");

        return self::SUCCESS;
    }

    private function ambilOgImage(string $url): ?string
    {
        try {
            $res = Http::withHeaders(['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/124.0 Safari/537.36'])
                ->withOptions(['verify' => false])->timeout(20)->get($url);
        } catch (\Throwable) {
            return null;
        }
        if (!$res->ok()) {
            return null;
        }

        $html = $res->body();
        if (preg_match('/<meta[^>]+(?:property|name)=["\'](?:og:image|twitter:image)["\'][^>]*content=["\']([^"\']+)["\']/i', $html, $m)
            || preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]*(?:property|name)=["\'](?:og:image|twitter:image)["\']/i', $html, $m)) {
            $img = html_entity_decode($m[1]);
            if (str_starts_with($img, '//')) {
                $img = 'https:'.$img;
            }

            return preg_match('#^https?://#i', $img) && !preg_match('/logo|favicon|default/i', $img) ? $img : null;
        }

        return null;
    }
}
