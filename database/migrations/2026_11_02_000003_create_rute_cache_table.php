<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Cache hasil rute OSRM agar layanan publik routing tidak dibebani berulang. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rute_cache', function (Blueprint $table) {
            $table->id();
            $table->string('mode', 20);
            $table->decimal('lat_asal', 9, 5);
            $table->decimal('lng_asal', 9, 5);
            $table->decimal('lat_tujuan', 9, 5);
            $table->decimal('lng_tujuan', 9, 5);
            $table->unsignedInteger('jarak_m');
            $table->unsignedInteger('durasi_s');
            $table->json('geometri')->nullable();
            $table->string('provider', 40)->default('osrm');
            $table->timestamps();

            $table->unique(['mode', 'lat_asal', 'lng_asal', 'lat_tujuan', 'lng_tujuan'], 'rute_cache_unik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rute_cache');
    }
};
