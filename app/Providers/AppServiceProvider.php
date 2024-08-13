<?php

namespace App\Providers;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

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
        Carbon::setLocale('uz_Latn');
        Carbon::macro('formatLocalized', function ($format) {
            $translatedFormat = strtr($format, [
                'F' => '%B',
                'l' => '%A',
                'D' => '%a',
            ]);

            setlocale(LC_TIME, 'uz_UZ.utf8', 'uz_UZ.UTF-8');
            return strftime($translatedFormat, $this->timestamp);
        });
    }
}
