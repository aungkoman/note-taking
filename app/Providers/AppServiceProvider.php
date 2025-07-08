<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            $user = auth('api')->user();

            if ($user?->role_id === 1) {
                Filament::registerResources([
                    \App\Filament\Resources\UserResource::class,
                    \App\Filament\Resources\NoteResource::class,
                ]);
            } elseif ($user?->role_id === 2) {
                Filament::registerResources([
                    \App\Filament\Resources\NoteResource::class,
                ]);
            }
        });
    }
}
