<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kos extends Model
{
    use HasFactory;

    protected $table = 'kos';

    public const TIPE = ['Putra', 'Putri', 'Campur', 'Belum diketahui'];

    /** Nilai tipe yang dapat dipakai sebagai filter pencarian publik. */
    public const TIPE_FILTER = ['Putra', 'Putri', 'Campur'];

    public const STATUS = ['Tersedia', 'Penuh'];

    public const STATUS_DATA = [
        'terverifikasi' => 'Terverifikasi',
        'perlu_konfirmasi' => 'Perlu dikonfirmasi',
        'contoh' => 'Data contoh',
    ];

    public const KOORDINAT_STATUS = [
        'google' => 'Pin Google Maps (belum dicek lapangan)',
        'akurat' => 'Akurat (titik bangunan)',
        'perkiraan' => 'Perkiraan (area/jalan)',
    ];

    protected $fillable = [
        'nama_kos',
        'alamat',
        'provinsi',
        'kota',
        'kecamatan',
        'latitude',
        'longitude',
        'koordinat_status',
        'harga',
        'tipe',
        'deskripsi',
        'status',
        'status_data',
        'catatan_data',
        'foto',
        'kontak_nama',
        'kontak_telepon',
        'sumber_nama',
        'sumber_url',
        'terakhir_dicek',
        'geocoding_status', 'geocoding_source', 'geocoding_score', 'geocoded_at', 'geocoding_query',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'harga' => 'integer',
            'terakhir_dicek' => 'date',
            'geocoding_score' => 'integer',
            'geocoded_at' => 'datetime',
        ];
    }

    // ---------------------------------------------------------------- relasi

    public function fasilitas(): HasMany
    {
        return $this->hasMany(Fasilitas::class, 'kos_id');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ----------------------------------------------------------------- scope

    public function scopeTersedia(Builder $query): Builder
    {
        return $query->where('status', 'Tersedia');
    }

    public function scopeCari(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);
        if ($term === '') {
            return $query;
        }

        $like = '%'.$term.'%';

        return $query->where(function (Builder $w) use ($like) {
            $w->where('nama_kos', 'like', $like)
                ->orWhere('alamat', 'like', $like)
                ->orWhere('kecamatan', 'like', $like)
                ->orWhere('kota', 'like', $like)
                ->orWhere('deskripsi', 'like', $like);
        });
    }

    // ------------------------------------------------------------- accessor

    protected function punyaKoordinat(): Attribute
    {
        return Attribute::get(fn (): bool => $this->latitude !== null && $this->longitude !== null);
    }

    protected function fotoUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            $foto = $this->foto;
            if (! $foto) {
                return null;
            }
            if (str_starts_with($foto, 'http://') || str_starts_with($foto, 'https://')) {
                return $foto;
            }

            return asset('storage/'.ltrim($foto, '/'));
        });
    }

    protected function hargaLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->harga === null
            ? 'Harga belum diketahui'
            : 'Rp '.number_format($this->harga, 0, ',', '.'));
    }

    protected function statusDataLabel(): Attribute
    {
        return Attribute::get(fn (): string => self::STATUS_DATA[$this->status_data] ?? 'Perlu dikonfirmasi');
    }

    protected function googleMapsUrl(): Attribute
    {
        return Attribute::get(function (): string {
            if ($this->punya_koordinat) {
                $query = $this->latitude.','.$this->longitude;
            } else {
                $query = trim($this->nama_kos.' '.$this->alamat.' '.$this->kota);
            }

            return 'https://www.google.com/maps/search/?api=1&query='.urlencode($query);
        });
    }

    protected function whatsappUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            $digits = preg_replace('/\D+/', '', (string) $this->kontak_telepon);
            if ($digits === '' || $digits === null) {
                return null;
            }
            if (! preg_match('/^(08|628)/', $digits)) {
                return null; // telepon rumah: tidak ada WhatsApp
            }
            if (str_starts_with($digits, '0')) {
                $digits = '62'.substr($digits, 1);
            }
            $text = 'Halo, saya melihat '.$this->nama_kos.' di Maply. Apakah masih ada kamar kosong?';

            return 'https://wa.me/'.$digits.'?text='.rawurlencode($text);
        });
    }

    protected function tipeSlug(): Attribute
    {
        return Attribute::get(fn (): string => \Illuminate\Support\Str::slug((string) $this->tipe) ?: 'belum-diketahui');
    }

    protected function koordinatLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->punya_koordinat
            ? (self::KOORDINAT_STATUS[$this->koordinat_status] ?? 'Perkiraan (area/jalan)')
            : 'Belum tersedia');
    }

    protected function isContoh(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status_data === 'contoh');
    }

    // ---------------------------------------------------------------- peta

    /**
     * Data ringkas untuk penanda peta (dikirim ke JavaScript sebagai JSON).
     *
     * @return array<string, mixed>
     */

    /** Alamat untuk popup publik: hilangkan nomor rumah serta RT/RW demi privasi. */
    private function alamatPetaPublik(): string
    {
        $alamat = trim((string) $this->alamat);
        $alamat = preg_replace('/\b(?:nomor|no\.?|number)\s*[:.]?\s*[0-9A-Za-z\/-]+/iu', '', $alamat);
        $alamat = preg_replace('/\b(?:RT|RW)\s*[:.]?\s*[0-9\/-]+/iu', '', $alamat);
        $alamat = preg_replace('/\s*,\s*,+/', ', ', $alamat);
        return trim($alamat, " ,.-\t\n\r\0\x0B");
    }

    /** Untuk pin perkiraan, tautan eksternal mencari kawasan/alamat umum, bukan koordinat persis. */
    private function mapsAreaUrl(): string
    {
        if (($this->koordinat_status ?? null) === 'perkiraan') {
            $query = trim(implode(', ', array_filter([
                $this->alamatPetaPublik(), $this->kecamatan, $this->kota, $this->provinsi,
            ])));
            return 'https://www.google.com/maps/search/?api=1&query='.urlencode($query);
        }
        return $this->google_maps_url;
    }

    public function toMapData(?float $jarak = null, ?string $refKey = null): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama_kos,
            'alamat' => $this->alamatPetaPublik(),
            'kota' => $this->kota,
            'lat' => $this->latitude,
            'lng' => $this->longitude,
            'harga' => $this->harga,
            'tipe' => $this->tipe,
            'kecamatan' => $this->kecamatan,
            'fasilitas' => $this->relationLoaded('fasilitas')
                ? $this->fasilitas->pluck('nama_fasilitas')->values()->all()
                : $this->fasilitas()->pluck('nama_fasilitas')->all(),
            'statusData' => $this->status_data_label,
            'statusCode' => $this->status_data,
            'koordinat' => $this->koordinat_label,
            'jarak' => $jarak !== null ? round($jarak, 1) : null,
            'contoh' => $this->is_contoh,
            'url' => $refKey ? route('kos.show', [$this, 'kampus' => $refKey]) : route('kos.show', $this),
            'maps' => $this->mapsAreaUrl(),
        ];
    }
}
