<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Layanan Pihak Ketiga
    |--------------------------------------------------------------------------
    | File ini hilang dari proyek sehingga config('services.gemini.*') bernilai
    | null (dipakai chatbot Moy AI dan AI Kost Manager). Dipulihkan dengan nilai
    | bawaan yang sama seperti fallback di kode: model 'gemini-2.5-flash'.
    */

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
    ],

];
