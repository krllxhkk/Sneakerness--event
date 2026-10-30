<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class OrganisatorTicketProvider extends ServiceProvider
{
    /**
     * Registreer onderdelen voor het ticketbeheer.
     */
    public function register(): void
    {
        // Er zijn geen extra registraties nodig.
    }

    /**
     * Registreer een controle voor de ticketroutes.
     */
    public function boot(): void
    {
        // Registreer wanneer een organisator een ticketpagina opent.
        Route::matched(function ($gebeurtenis) {
            $routenaam = $gebeurtenis->route->getName();

            if ($routenaam !== null && str_starts_with($routenaam, 'organisator.tickets.')) {
                Log::info('Organisator heeft een ticketroute geopend.', [
                    'route' => $routenaam,
                ]);
            }
        });
    }
}
