<?php

namespace App\Http\Requests;

use App\Models\Kos;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('kelola-kos') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        // Harga boleh diketik "1.200.000" atau "Rp 1200000".
        if ($this->has('harga')) {
            $digits = preg_replace('/\D+/', '', (string) $this->input('harga'));
            $merge['harga'] = ($digits === '' || $digits === null) ? null : $digits;
        }
        // Koordinat boleh memakai koma desimal.
        foreach (['latitude', 'longitude'] as $field) {
            if ($this->filled($field)) {
                $merge[$field] = str_replace(',', '.', trim((string) $this->input($field)));
            }
        }

        if ($merge) {
            $this->merge($merge);
        }
    }

    public function rules(): array
    {
        return [
            'nama_kos' => ['required', 'string', 'min:3', 'max:150'],
            'tipe' => ['required', Rule::in(Kos::TIPE)],
            'status' => ['required', Rule::in(Kos::STATUS)],
            'harga' => ['nullable', 'integer', 'min:50000', 'max:50000000'],
            'alamat' => ['required', 'string', 'min:5', 'max:500'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kota' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'required_with:longitude', 'numeric', 'between:-6.5,-1'],
            'longitude' => ['nullable', 'required_with:latitude', 'numeric', 'between:102,107'],
            'koordinat_status' => ['nullable', Rule::in(array_keys(Kos::KOORDINAT_STATUS))],
            'deskripsi' => ['nullable', 'string', 'max:3000'],
            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['string', Rule::in(config('maply.fasilitas'))],
            'fasilitas_lain' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'foto_url' => ['nullable', 'url:http,https', 'max:500'],
            'hapus_foto' => ['nullable', 'boolean'],
            'kontak_nama' => ['nullable', 'string', 'max:100'],
            'kontak_telepon' => ['nullable', 'regex:/^\+?[0-9\s\-]{8,20}$/'],
            'sumber_nama' => ['nullable', 'string', 'max:150'],
            'sumber_url' => ['nullable', 'url:http,https', 'max:500'],
            'status_data' => ['required', Rule::in(array_keys(Kos::STATUS_DATA))],
            'terakhir_dicek' => ['nullable', 'date', 'before_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.between' => 'Latitude harus berada di wilayah Sumatera Selatan (antara -6,5 dan -1). Pastikan latitude dan longitude tidak tertukar.',
            'longitude.between' => 'Longitude harus berada di wilayah Sumatera Selatan (antara 102 dan 107). Pastikan latitude dan longitude tidak tertukar.',
            'kontak_telepon.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, strip, dan tanda + di awal (8–20 karakter).',
            'harga.min' => 'Harga minimal Rp50.000 per bulan. Kosongkan bila harga belum diketahui.',
        ];
    }
}
