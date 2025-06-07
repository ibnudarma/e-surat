<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;

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
        View::composer('*', function ($view) {
        $jumlah = \App\Models\Surat::where('tgl_diterima', '=',null)
            ->where('ditujukan', auth()->user()?->bagian_id)
            ->count();

        $view->with('smbd', $jumlah);
        });
    }
}
