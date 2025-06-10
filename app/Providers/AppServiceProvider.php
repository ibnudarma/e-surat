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
        $jumlahSuratUmum = \App\Models\Surat::where('tgl_diterima', '=',null)
            ->where('ditujukan','=', auth()->user()?->bagian_id)
            ->count();
        $jumlahDisposisiSekda = \App\Models\LembarDisposisiSekda::where('tgl_diterima', '=',null)
            ->where('ditujukan','=', auth()->user()?->bagian_id)
            ->count();
        $jumlahDisposisiAsda = \App\Models\LembarDisposisiAsda::where('tgl_diterima', '=',null)
            ->where('ditujukan','=', auth()->user()?->bagian_id)
            ->count();
        $jumlahKartuDisposisi = \App\Models\KartuDisposisi::where('tgl_diterima_asda', '=',null)
            ->where('ditujukan','=', auth()->user()?->bagian_id)
            ->count();

        $view->with('smbd', $jumlahSuratUmum);
        $view->with('jds', $jumlahDisposisiSekda);
        $view->with('jda', $jumlahDisposisiAsda);
        $view->with('jkd', $jumlahKartuDisposisi);

        });
    }
}
