<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kos', function (Blueprint $table) {
            if (! Schema::hasColumn('kos', 'geocoding_status')) $table->string('geocoding_status', 30)->nullable()->index();
            if (! Schema::hasColumn('kos', 'geocoding_source')) $table->string('geocoding_source', 100)->nullable();
            if (! Schema::hasColumn('kos', 'geocoding_score')) $table->unsignedTinyInteger('geocoding_score')->nullable();
            if (! Schema::hasColumn('kos', 'geocoded_at')) $table->timestamp('geocoded_at')->nullable();
            if (! Schema::hasColumn('kos', 'geocoding_query')) $table->text('geocoding_query')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('kos', function (Blueprint $table) {
            foreach (['geocoding_status', 'geocoding_source', 'geocoding_score', 'geocoded_at', 'geocoding_query'] as $column) {
                if (Schema::hasColumn('kos', $column)) $table->dropColumn($column);
            }
        });
    }
};
