<?php

namespace App\Http\Controllers;

use App\Models\KampusLokasi;
use App\Models\Kos;
use App\Services\MapsUrl;
use App\Services\RuteService;
use Illuminate\Http\JsonResponse;

/**
 * API kecil untuk peta: rute kos → kampus (OSRM via jalan, bukan garis lurus).
 * Return JSON: jarak rute, waktu, geometri, dan tautan Google Maps directions
 * yang memakai koordinat database yang sama.
 */
class RuteApiController extends Controller
{
    public function kosKeKampus(Kos $kos, KampusLokasi $lokasi, \Illuminate\Http\Request $request): JsonResponse
    {
        $mode = $request->query('mode', 'driving');
        if (!in_array($mode, RuteService::MODA, true)) {
            $mode = 'driving';
        }

        if (!$kos->punya_koordinat || !$lokasi->punya_koordinat) {
            return response()->json(['ok' => false, 'pesan' => 'Koordinat kos atau kampus belum tersedia.'], 422);
        }

        $svc = new RuteService;
        $rute = $svc->rute($kos->latitude, $kos->longitude, $lokasi->latitude, $lokasi->longitude, $mode);

        // Jarak garis lurus TETAP dikirim terpisah — jangan dicampur jarak rute.
        $garisLurus = RuteService::jarakGarisLurusKm($kos->latitude, $kos->longitude, $lokasi->latitude, $lokasi->longitude);

        return response()->json([
            'ok' => (bool) $rute,
            'mode' => $mode,
            'pesan' => $rute
                ? 'Rute '.$this->labelMode($mode).' dari layanan routing OpenStreetMap (OSRM).'
                : ($mode === 'foot'
                    ? 'Rute pejalan kaki tidak tersedia dari layanan routing untuk pasangan titik ini — jangan dianggap jalur jalan kaki yang valid.'
                    : 'Rute tidak dapat dihitung layanan routing saat ini. Coba lagi nanti.'),
            'garis_lurus_km' => round($garisLurus, 2),
            'rute' => $rute ? [
                'jarak_km' => round($rute['jarak_m'] / 1000, 2),
                'durasi_label' => RuteService::durasiLabel($rute['detik']),
                'geometri' => $rute['geometri'],
                'sumber' => $rute['sumber'],
            ] : null,
            'google_maps' => MapsUrl::directions($kos->latitude, $kos->longitude, $lokasi->latitude, $lokasi->longitude, $mode === 'foot' ? 'walking' : 'driving'),
            'asal' => ['lat' => $kos->latitude, 'lng' => $kos->longitude, 'nama' => $kos->nama_kos],
            'tujuan' => ['lat' => $lokasi->latitude, 'lng' => $lokasi->longitude, 'nama' => $lokasi->kampus->label.' — '.$lokasi->nama_lokasi],
        ]);
    }

    protected function labelMode(string $mode): string
    {
        return match ($mode) {
            'foot' => 'pejalan kaki',
            'bike' => 'sepeda',
            default => 'kendaraan',
        };
    }
}
