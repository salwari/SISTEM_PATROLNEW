<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Patroli;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        }

    public function boot()
    {
        Carbon::setLocale('id');

        View::composer('*', function ($view) {
            $jumlahValidasiPending = Patroli::where('status', 'darurat')
                ->whereNull('validasi_darurat')
                ->count();

            $view->with('jumlahValidasiPending', $jumlahValidasiPending);
        });
    }
}
