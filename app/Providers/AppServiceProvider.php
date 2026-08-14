<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Models\Ajuste;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

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
            $ajuste = Ajuste::first();
            $simbolo = '$'; // Valor por defecto

            if ($ajuste && $ajuste->divisa) {
                $path = public_path('divisas.json');
                if (File::exists($path)) {
                    $json = File::get($path);
                    $divisas = json_decode($json, true);

                    // Buscamos la divisa cuyo código coincida con el guardado en ajustes (ej. 'USD', 'BOB', etc.)
                    foreach ($divisas as $divisa) {
                        // Dependiendo de la estructura de tu JSON, usualmente se busca por 'code' o 'simbolo'/'symbol'
                        if (isset($divisa['code']) && $divisa['code'] === $ajuste->divisa) {
                            $simbolo = $divisa['symbol'] ?? $divisa['simbolo'] ?? $ajuste->divisa;
                            break;
                        }
                    }
                } else {
                    $simbolo = $ajuste->divisa; // Si no encuentra el archivo, usa el texto guardado directamente
                }
            }

            // Compartir la variable $simboloMoneda a todas las vistas
            $view->with('simboloMoneda', $simbolo);
        });

        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
