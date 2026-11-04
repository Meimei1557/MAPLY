<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        // 1. Validasi pesan dan riwayat percakapan.
        $data = $request->validate([
            'message' => ['required', 'string'],
            'history' => ['sometimes', 'array', 'max:60'],
            'history.*.role' => ['required', 'in:user,model'],
            'history.*.text' => ['required', 'string'],
            'summary' => ['sometimes', 'nullable', 'string', 'max:8000'],
        ]);

        $message = trim($data['message']);
        $summary = trim((string) ($data['summary'] ?? ''));

        $history = collect($data['history'] ?? [])
            ->map(fn ($turn) => [
                'role' => $turn['role'],
                'parts' => [
                    ['text' => trim($turn['text'])]
                ],
            ])
            ->filter(fn ($turn) => $turn['parts'][0]['text'] !== '')
            ->values()
            ->all();

        // 2. Hitung statistik seluruh data kost.
        $totalKost = Kos::count();

        $totalPalembang = Kos::whereRaw(
            'LOWER(TRIM(kota)) = ?',
            ['palembang']
        )->count();

        $totalIndralaya = Kos::whereRaw(
            'LOWER(TRIM(kota)) = ?',
            ['indralaya']
        )->count();

        $totalTersediaTerverifikasi = Kos::query()
            ->tersedia()
            ->where('status_data', 'terverifikasi')
            ->count();

        $statistics = [
            'total_kost' => $totalKost,
            'palembang' => $totalPalembang,
            'indralaya' => $totalIndralaya,
            'tersedia_terverifikasi' => $totalTersediaTerverifikasi,
        ];

        // 3. Kenali konteks percakapan.
        $latestMessage = mb_strtolower($message);

        $conversationText = mb_strtolower(
            $summary . ' ' .
            collect($data['history'] ?? [])
                ->where('role', 'user')
                ->pluck('text')
                ->implode(' ') . ' ' .
            $message
        );

        $kostKeywords = [
            'kos',
            'kost',
            'indekos',
            'kontrakan',
            'sewa kamar',
            'tempat tinggal',
            'penginapan',
        ];

        $isKostContext = collect($kostKeywords)->contains(
            fn ($keyword) => str_contains($conversationText, $keyword)
        );

        // Deteksi pertanyaan jumlah kost.
        $countKeywords = [
            'berapa kost',
            'berapa kos',
            'ada berapa kost',
            'ada berapa kos',
            'jumlah kost',
            'jumlah kos',
            'total kost',
            'total kos',
            'berapa banyak kost',
            'berapa banyak kos',
            'jumlah data kost',
            'jumlah data kos',
        ];

        $isKostCountQuestion = collect($countKeywords)->contains(
            fn ($keyword) => str_contains($latestMessage, $keyword)
        );

        // Deteksi permintaan pencarian kost secara langsung.
        $searchKeywords = [
            'carikan kost',
            'carikan kos',
            'cari kost',
            'cari kos',
            'tampilkan kost',
            'tampilkan kos',
            'rekomendasi kost',
            'rekomendasi kos',
            'daftar kost',
            'daftar kos',
            'kost di',
            'kos di',
            'kost dekat',
            'kos dekat',
            'kost putri',
            'kost putra',
            'kost campur',
            'harga kost',
            'harga kos',
            'kost termurah',
            'kos termurah',
            'kost termahal',
            'kos termahal',
            'cari tempat tinggal',
        ];

        $isExplicitKostSearch = collect($searchKeywords)->contains(
            fn ($keyword) => str_contains($latestMessage, $keyword)
        );

        // Deteksi pertanyaan lanjutan dari pencarian sebelumnya.
        $followUpKeywords = [
            'yang paling murah',
            'yang termurah',
            'yang termahal',
            'yang paling mahal',
            'yang paling dekat',
            'yang dekat',
            'lebih dekat',
            'pilihan tadi',
            'dari pilihan',
            'tampilkan lagi',
            'yang lain',
            'lainnya',
            'yang putri',
            'yang putra',
            'yang campur',
            'yang ada wifi',
            'yang ada ac',
            'dekat kampus',
            'sekitar kampus',
            'di bawah',
            'dibawah',
            'maksimal',
            'budget',
            'anggaran',
            'ada yang',
            'mana yang',
        ];

        $isKostFollowUp = $isKostContext && collect($followUpKeywords)->contains(
            fn ($keyword) => str_contains($latestMessage, $keyword)
        );

        // Kartu hanya muncul jika ada permintaan pencarian.
        $isKostSearch = (
            $isExplicitKostSearch || $isKostFollowUp
        ) && !$isKostCountQuestion;

        $showKostCards = $isKostSearch;

        $results = collect();
        $totalMatched = 0;

        // 4. Cari rekomendasi kost hanya jika diminta.
        if ($isKostSearch) {
            $query = Kos::query()
                ->with('fasilitas')
                ->tersedia()
                ->where('status_data', 'terverifikasi');

            // Filter kota.
            if (
                str_contains($conversationText, 'indralaya') ||
                str_contains($conversationText, 'unsri')
            ) {
                $query->whereRaw(
                    'LOWER(TRIM(kota)) = ?',
                    ['indralaya']
                );
            } elseif (str_contains($conversationText, 'palembang')) {
                $query->whereRaw(
                    'LOWER(TRIM(kota)) = ?',
                    ['palembang']
                );
            }

            // Filter harga maksimal.
            if (preg_match(
                '/(?:di bawah|dibawah|maksimal|max|kurang dari|budget|anggaran)\s*rp?\s*([\d.,]+)/u',
                $conversationText,
                $matches
            )) {
                $amount = (int) preg_replace(
                    '/\D/',
                    '',
                    $matches[1]
                );

                if ($amount > 0) {
                    $query->where('harga', '<=', $amount);
                }
            }

            // Filter tipe kost.
            if (str_contains($conversationText, 'putri')) {
                $query->where('tipe', 'Putri');
            } elseif (str_contains($conversationText, 'putra')) {
                $query->where('tipe', 'Putra');
            } elseif (str_contains($conversationText, 'campur')) {
                $query->where('tipe', 'Campur');
            }

            // Filter fasilitas.
            foreach ([
                'wifi' => 'WiFi',
                'ac' => 'AC',
                'parkir' => 'Parkir',
                'dapur' => 'Dapur',
                'kamar mandi dalam' => 'Kamar Mandi Dalam',
            ] as $term => $facility) {
                if (str_contains($conversationText, $term)) {
                    $query->whereHas(
                        'fasilitas',
                        fn ($q) => $q->where('nama_fasilitas', $facility)
                    );
                }
            }

            // Hitung seluruh hasil yang sesuai filter.
            $totalMatched = (clone $query)->count();

            // Tampilkan maksimal 8 rekomendasi.
            $results = $query->limit(8)->get();
        }

        // 5. Siapkan data kost untuk antarmuka.
        $items = $results->map(fn ($kos) => [
            'id' => $kos->id,
            'name' => $kos->nama_kos,
            'address' => $kos->alamat,
            'city' => $kos->kota,
            'price' => $kos->harga,
            'type' => $kos->tipe,
            'facilities' => $kos->fasilitas
                ->pluck('nama_fasilitas')
                ->values(),
            'url' => route('kos.show', $kos),
            'maps' => $kos->google_maps_url,
        ])->values();

        // 6. Fungsi respons.
        // Variabel showKostCards harus dimasukkan ke dalam use().
        $makeResponse = function (
            string $answer,
            string $ai,
            ?string $responseSummary = null
        ) use (
            $items,
            $statistics,
            $totalMatched,
            $showKostCards
        ): JsonResponse {
            return response()->json([
                'message' => $answer,
                'items' => $showKostCards ? $items : [],
                'ai' => $ai,
                'summary' => $responseSummary ?? '',
                'statistics' => $statistics,
                'total_matched' => $totalMatched,
            ]);
        };

        // 7. Konfigurasi .
        $apiKey = config('services.gemini.key');

        $model = config(
            'services.gemini.model',
            'gemini-2.5-flash'
        );

        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/'
            . rawurlencode($model)
            . ':generateContent';

        $newSummary = $summary;

        if (!is_string($apiKey) || trim($apiKey) === '') {
            return $makeResponse(
                'Gemini belum diaktifkan. Silakan periksa konfigurasi API Key. '
                . 'Statistik database tetap tersedia.',
                'database_fallback',
                $newSummary
            );
        }

        // 8. Siapkan konteks rekomendasi kost.
        $kosContext = $items->map(fn ($item) => [
            'nama' => $item['name'],
            'alamat' => $item['address'],
            'kota' => $item['city'],
            'harga_per_bulan' => $item['price'],
            'tipe' => $item['type'],
            'fasilitas' => $item['facilities'],
            'url_detail' => $item['url'],
            'url_peta' => $item['maps'],
        ])->all();

        // 9. Instruksi utama Moy AI.
        $systemInstruction =
            "Kamu adalah Moy AI, asisten pencarian kos Maply. Perkenalkan dirimu sebagai Moy AI bila ditanya namamu. "

            . "STATISTIK DATABASE MAPLY (DATA LANGSUNG DARI DATABASE): "
            . "Total seluruh data kost: {$totalKost}. "
            . "Total data kost di Palembang: {$totalPalembang}. "
            . "Total data kost di Indralaya: {$totalIndralaya}. "
            . "Total kost tersedia dan terverifikasi: {$totalTersediaTerverifikasi}. "
            . "Jumlah hasil pencarian sesuai filter saat ini: {$totalMatched}. "

            . "Jika pengguna bertanya jumlah seluruh kost, gunakan total seluruh data kost. "
            . "Jika pengguna bertanya jumlah kost di Palembang, gunakan statistik Palembang. "
            . "Jika pengguna bertanya jumlah kost di Indralaya, gunakan statistik Indralaya. "
            . "Jika pengguna bertanya jumlah kost tersedia dan terverifikasi, gunakan statistik tersebut. "
            . "Jangan mengarang atau mengubah angka statistik dari database. "

            . "Kamu boleh mengobrol dan menjawab pertanyaan umum dalam bahasa Indonesia. "
            . "Jawablah secara alami, jelas, dan ramah. "
            . "Jangan selalu mengarahkan percakapan ke pencarian kost. "

            . "Jika pengguna meminta pencarian atau rekomendasi kost, gunakan hanya DATA KOST MAPLY yang disertakan. "
            . "Jangan mengarang nama, harga, alamat, fasilitas, ketersediaan, atau tautan kost. "
            . "Data rekomendasi hanya berisi kost berstatus tersedia dan terverifikasi. "
            . "Jika data kosong atau tidak cocok, katakan dengan jujur bahwa belum ada data yang cocok. "
            . "Jika harga bernilai null, jelaskan bahwa harga belum tercantum. "
            . "Jangan menyatakan bahwa kamu telah menghubungi pemilik atau memeriksa ketersediaan secara langsung. "
            . "Jika pengguna mengobrol umum, jawab pertanyaannya tanpa memaksakan rekomendasi kost. "

            . "DATA KOST MAPLY (JSON): "
            . json_encode(
                $kosContext,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );

        // 10. Ringkas riwayat lama jika percakapan terlalu panjang.
        if (count($history) > 16) {
            $olderTurns = array_slice($history, 0, -12);
            $recentTurns = array_slice($history, -12);

            $transcript = collect($olderTurns)
                ->map(function ($turn) {
                    $role = $turn['role'] === 'user'
                        ? 'Pengguna'
                        : 'Moy AI';

                    return $role . ': ' . $turn['parts'][0]['text'];
                })
                ->implode("\n");

            $summaryPrompt =
                "Buat ringkasan konteks percakapan berikut untuk melanjutkan obrolan. "
                . "Pertahankan tujuan pengguna, preferensi, batasan, lokasi, anggaran, "
                . "pilihan kost, fakta penting, dan hal yang belum terselesaikan. "
                . "Jangan menambahkan fakta baru. Gunakan bahasa Indonesia yang padat. "
                . "Ringkasan sebelumnya: "
                . ($summary !== '' ? $summary : '(belum ada)')
                . "\nPercakapan lama yang perlu diringkas:\n"
                . $transcript;

            try {
                $summaryResponse = Http::connectTimeout(5)
                    ->timeout(20)
                    ->withHeaders([
                        'x-goog-api-key' => $apiKey
                    ])
                    ->post($endpoint, [
                        'contents' => [[
                            'role' => 'user',
                            'parts' => [[
                                'text' => $summaryPrompt
                            ]],
                        ]],
                        'generationConfig' => [
                            'temperature' => 0.2,
                            'maxOutputTokens' => 700,
                        ],
                    ]);

                if ($summaryResponse->successful()) {
                    $candidateSummary = trim((string) data_get(
                        $summaryResponse->json(),
                        'candidates.0.content.parts.0.text',
                        ''
                    ));

                    if ($candidateSummary !== '') {
                        $newSummary = mb_substr(
                            $candidateSummary,
                            0,
                            8000
                        );

                        $history = $recentTurns;
                    }
                } else {
                    Log::warning(
                        'Moy AI conversation summarization failed',
                        [
                            'status' => $summaryResponse->status(),
                        ]
                    );
                }
            } catch (\Throwable $summaryException) {
                Log::warning(
                    'Moy AI conversation summarization error',
                    [
                        'error' => $summaryException->getMessage(),
                    ]
                );
            }
        }

        if ($newSummary !== '') {
            $systemInstruction .=
                "\nRINGKASAN PERCAKAPAN SEBELUMNYA:\n"
                . $newSummary;
        }

        // 11. Susun riwayat dan pesan terbaru.
        $contents = $history;

        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $message]
            ],
        ];

        // 12. Kirim permintaan ke Gemini.
        try {
            $response = Http::connectTimeout(5)
                ->timeout(25)
                ->withHeaders([
                    'x-goog-api-key' => $apiKey
                ])
                ->post($endpoint, [
                    'systemInstruction' => [
                        'parts' => [[
                            'text' => $systemInstruction
                        ]],
                    ],
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 800,
                    ],
                ]);

            if (!$response->successful()) {
                Log::warning('Moy AI Gemini request failed', [
                    'status' => $response->status(),
                    'body' => mb_substr(
                        $response->body(),
                        0,
                        1000
                    ),
                ]);

                return $makeResponse(
                    $showKostCards
                        ? 'Maaf, layanan AI sedang sibuk. Data kost yang cocok tetap dapat dilihat di bawah.'
                        : 'Maaf, layanan AI sedang sibuk. Silakan coba lagi beberapa saat.',
                    'database_fallback',
                    $newSummary
                );
            }

            $answer = trim((string) data_get(
                $response->json(),
                'candidates.0.content.parts.0.text',
                ''
            ));

            if ($answer === '') {
                return $makeResponse(
                    'Maaf, saya belum mendapatkan jawaban. Silakan coba dengan pertanyaan lain.',
                    'database_fallback',
                    $newSummary
                );
            }

            return $makeResponse(
                $answer,
                'gemini',
                $newSummary
            );

        } catch (\Throwable $exception) {
            Log::warning('Moy AI Gemini connection failed', [
                'error' => $exception->getMessage(),
            ]);

            return $makeResponse(
                $showKostCards
                    ? 'Koneksi AI sedang bermasalah. Data kost yang cocok tetap dapat dilihat di bawah.'
                    : 'Koneksi AI sedang bermasalah. Silakan coba lagi beberapa saat.',
                'database_fallback',
                $newSummary
            );
        }
    }
}