<?php

namespace App\Providers;

use App\Models\User;
use App\Support\AiData\SourceConnectorManager;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Manager connector sumber data AI (driver pattern) dibangun dari config/ai_pipeline.php.
        $this->app->singleton(SourceConnectorManager::class, fn () => SourceConnectorManager::fromConfig());
    }

    public function boot(): void
    {
        // Hanya administrator yang boleh menambah, mengubah, dan menghapus data kos.
        Gate::define('kelola-kos', fn (User $user): bool => $user->isAdmin());
    }
}
