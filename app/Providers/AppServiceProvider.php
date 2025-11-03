<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\DataPemilih;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
        $jenisPemiluList = DataPemilih::select('jenis_pemilu')->distinct()->pluck('jenis_pemilu');
        $jenisPemilu = session('jenis_pemilu', $jenisPemiluList->first());

        $view->with(compact('jenisPemiluList', 'jenisPemilu'));
    });
    }
}
