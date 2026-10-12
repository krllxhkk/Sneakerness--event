
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tickets | Sneakerness®</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/organisator-tickets.css') }}">
</head>

<body>

    <!-- Bovenste navigatie -->
    <header class="dashboard-header">

        <div class="dashboard-logo">
            SNEAKERNESS<span>®</span>
        </div>

        <!-- Hamburgermenu voor kleine schermen -->
        <button
            type="button"
            class="burger-menu"
            id="burgerMenu"
            aria-label="Open navigatiemenu"
            aria-controls="mobielMenu"
            aria-expanded="false"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

        <!-- Navigatie -->
        <nav class="dashboard-nav" id="mobielMenu">
            <a href="{{ route('organisator.dashboard') }}">
                Dashboard
            </a>

            <a href="{{ route('organisator.events') }}">
                Evenementen
            </a>

            <a href="{{ route('organisator.tickets.index') }}" class="actief">
                Tickets
            </a>

            <a href="{{ route('organisator.verkopers') }}">
                Verkopers
            </a>

            <a href="{{ route('organisator.stands') }}">
                Stands
            </a>
        </nav>

        <!-- Uitloggen -->
        <form
            method="POST"
            action="{{ route('logout') }}"
            class="uitloggen-formulier"
        >
            @csrf

            <button type="submit" class="uitloggen-knop">
                UITLOGGEN
            </button>
        </form>

    </header>

    <div class="dashboard-layout">

        <!-- Zijbalk voor de organisator -->
        <aside class="zijbalk">

            <p class="zijbalk-label">Organisator</p>

            <h2>Beheer Panel</h2>

            <nav class="beheer-menu">

                <a href="{{ route('organisator.dashboard') }}">
                    Dashboard
                </a>

                <a href="{{ route('organisator.events') }}">
                    Evenementen
                </a>

                <a href="{{ route('organisator.tickets.index') }}" class="actief">
                    Tickets
                </a>

                <a href="{{ route('organisator.verkopers') }}">
                    Verkopers
                </a>

                <a href="{{ route('organisator.stands') }}">
                    Stands
                </a>

            </nav>

        </aside>

        <!-- Hoofdinhoud -->
        <main class="dashboard-inhoud">

            <!-- Titel en knop -->
            <div class="tickets-kop">

                <div>
                    <h1>TICKETS</h1>
                    <p>{{ $tickets->count() }} tickettypes totaal</p>
                </div>

                <!-- Nieuw ticket toevoegen -->
                <a
                    href="{{ route('organisator.tickets.create') }}"
                    class="toevoegen-knop"
                >
                    TICKET TOEVOEGEN
                </a>

            </div>

            <!-- Succesmelding -->
            @if (session('success'))
                <div class="ticket-melding" role="status">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <!-- Foutmelding -->
            @if ($errorMessage)
                <p class="ticket-fout" role="alert">
                    {{ $errorMessage }}
                </p>
            @endif

            <!-- Zoekveld -->
            <input
                type="search"
                id="ticketsZoeken"
                class="zoekveld"
                placeholder="Zoek op ticket ID of datum..."
                aria-label="Zoek tickets"
            >

            <!-- Tabel met tickets -->
            <div class="tickets-tabel-wrapper">

                <table class="tickets-tabel">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Dag</th>
                            <th>Datum</th>
                            <th>Tijdslot</th>
                            <th>Aantal</th>
                            <th>Prijs</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody id="ticketsRijen">

                        @forelse ($tickets as $ticket)

                            <tr>

                                <!-- Ticketnummer -->
                                <td class="ticket-id">
                                    TK-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}
                                </td>

                                <!-- Dag -->
                                <td>
                                    {{ \Carbon\Carbon::parse($ticket->datum)->locale('nl')->translatedFormat('l') }}
                                </td>

                                <!-- Datum -->
                                <td>
                                    {{ \Carbon\Carbon::parse($ticket->datum)->format('d-m-Y') }}
                                </td>

                                <!-- Tijdslot -->
                                <td>
                                    {{ substr($ticket->tijdslot, 0, 5) }}
                                </td>

                                <!-- Aantal beschikbare tickets -->
                                <td>
                                    {{ $ticket->aantal_tickets_per_tijdslot }}
                                </td>

                                <!-- Prijs -->
                                <td class="ticket-prijs">
                                    €{{ number_format($ticket->tarief, 2, ',', '.') }}
                                </td>

                                <!-- Status -->
                                <td>
                                    <span class="ticket-status {{ $ticket->is_actief ? 'status-actief' : 'status-inactief' }}">
                                        {{ $ticket->is_actief ? 'Actief' : 'Inactief' }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7">
                                    Er zijn nog geen tickets beschikbaar.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </main>

    </div>

    <script>
        // =====================================
        // TICKETS ZOEKEN
        // =====================================

        const zoekveld = document.getElementById('ticketsZoeken');
        const rijen = document.querySelectorAll('#ticketsRijen tr');

        zoekveld.addEventListener('input', function () {
            const zoektekst = this.value.toLowerCase();

            rijen.forEach(function (rij) {
                const ticketgegevens = rij.textContent.toLowerCase();

                rij.style.display = ticketgegevens.includes(zoektekst)
                    ? ''
                    : 'none';
            });
        });


        // =====================================
        // HAMBURGERMENU
        // =====================================

        const burgerMenu = document.getElementById('burgerMenu');
        const mobielMenu = document.getElementById('mobielMenu');

        burgerMenu.addEventListener('click', function () {
            const isOpen = mobielMenu.classList.toggle('menu-open');

            burgerMenu.setAttribute(
                'aria-expanded',
                String(isOpen)
            );

            burgerMenu.setAttribute(
                'aria-label',
                isOpen
                    ? 'Sluit navigatiemenu'
                    : 'Open navigatiemenu'
            );
        });

    </script>

</body>
</html>
