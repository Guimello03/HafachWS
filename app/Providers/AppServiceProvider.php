<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Client;
use App\Models\School;

// 📌 Importações novas:
use App\Models\Student;
use App\Models\Guardian;
use App\Models\Functionary;
use App\Observers\DeviceGroupPersonObserver;

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
        // 📌 Registrar o observer para os três modelos
        Student::observe(DeviceGroupPersonObserver::class);
        Guardian::observe(DeviceGroupPersonObserver::class);
        Functionary::observe(DeviceGroupPersonObserver::class);

        // Apenas no layout principal: carregar lista de clientes (para super admin)
        View::composer('components.admin-layout', function ($view) {
            $clients = [];

            if (auth()->check() && auth()->user()->hasRole('super_admin')) {
                $clients = Client::all(['id', 'name']);
            }

            $view->with('clients', $clients);
        });

        // Em todas as views: carregar escola ativa com cliente incluso
        View::composer('*', function ($view) {
            $view->with('activeSchool', activeSchool());
        });

            

        Paginator::useTailwind();
    }
}
