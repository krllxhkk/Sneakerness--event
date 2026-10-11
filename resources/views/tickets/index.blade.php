<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets Kopen | Sneakerness® Rotterdam 2026</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Inter:wght@400;600;700;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/tickets.css') }}">
</head>

<body>
    <header>
        <div class="header-container">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="logo">
                SNEAKERNESS<sup>®</sup>
            </a>

            <!-- Hamburger menu -->
            <div class="hamburger" id="hamburgerBtn" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <!-- Navigatielinks -->
            <ul class="nav-links" id="navLinks">
                <li>
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('events.index') }}"
                        class="{{ request()->routeIs('events.index') ? 'active' : '' }}">
                        Event
                    </a>
                </li>
                <li>
                    <a href="{{ route('tickets.index') }}"
                        class="{{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                        Tickets
                    </a>
                </li>
                <li>
                    <a href="{{ route('verkopers.index') }}"
                        class="{{ request()->routeIs('verkopers.index') ? 'active' : '' }}">
                        Verkopers
                    </a>
                </li>
                <li>
                    <a href="{{ route('stands.index') }}"
                        class="{{ request()->routeIs('stands.index') ? 'active' : '' }}">
                        Stands
                    </a>
                </li>
            </ul>

            <!-- Rechterkant navigatie -->
            <div class="nav-right">
                @guest
                    <a href="{{ route('login') }}" class="login-link">
                        LOGIN
                    </a>

                    <a href="{{ route('register') }}" class="login-link">
                        REGISTREREN
                    </a>
                @endguest

                @auth
                    <form method="POST" action="{{ route('logout') }}" class="logout-formulier">
                        @csrf

                        <button type="submit" class="login-link logout-button">
                            UITLOGGEN
                        </button>
                    </form>
                @endauth

                {{-- Dashboard alleen voor de organisator --}}
                @auth
                    @if (auth()->user()->email === 'organisator@sneakerness.nl')
                        <a href="{{ route('organisator.dashboard') }}" class="btn-yellow-sm">
                            DASHBOARD
                        </a>
                    @endif
                @endauth

                <a href="#tickets" class="btn-yellow-sm">TICKET KOPEN</a>
            </div>
        </div>
    </header>

    <main class="main-container">

        <!-- Foutmeldingen voor bezoekers -->
        @if (!empty($errorMessage))
            <div class="error-alert" role="alert">
                {{ $errorMessage }}
            </div>
        @endif

        @if (session('error') && session('error') !== $errorMessage)
            <div class="error-alert" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-alert" role="alert">
                <ul class="foutenlijst">
                    @foreach ($errors->all() as $fout)
                        <li>{{ $fout }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="page-header">
            <span class="page-subtitle">
                SNEAKERNESS® ROTTERDAM 2026
            </span>

            <h1 class="page-title">
                TICKETS KOPEN
            </h1>
        </div>

        <!-- Vier stappen van het bestelproces -->
        <div class="stepper">
            @foreach (['Dag', 'Tijdslot', 'Gegevens', 'Overzicht'] as $nummer => $naam)
                @php
                    $stapNummer = $nummer + 1;
                @endphp

                <div class="step-item">
                    <div
                        class="step-circle {{ $stap === $stapNummer ? 'active' : ($stap > $stapNummer ? 'completed' : '') }}">
                        @if ($stap > $stapNummer)
                            &#10003;
                        @else
                            {{ $stapNummer }}
                        @endif
                    </div>

                    <span class="step-label {{ $stap === $stapNummer ? 'active' : '' }}">
                        {{ $naam }}
                    </span>
                </div>
            @endforeach
        </div>

        <!-- STAP 1: KIES JE DAG -->
        @if ($stap === 1)
            <h2 class="section-title">
                KIES JE DAG
            </h2>

            <div class="day-grid">
                <!-- Zaterdag -->
                <a href="{{ route('tickets.index', ['dag' => 'zaterdag', 'stap' => 1]) }}"
                    class="day-card {{ $gekozenDag === 'zaterdag' ? 'selected' : '' }}">

                    <div class="icon">📅</div>

                    <h3>ZATERDAG</h3>

                    <p>14 november 2026</p>

                    <p class="location">
                        Sneakerness® Rotterdam
                    </p>

                    @if ($gekozenDag === 'zaterdag')
                        <div class="check-badge">&#10003;</div>
                    @endif
                </a>

                <!-- Zondag -->
                <a href="{{ route('tickets.index', ['dag' => 'zondag', 'stap' => 1]) }}"
                    class="day-card {{ $gekozenDag === 'zondag' ? 'selected' : '' }}">

                    <div class="icon">☀️</div>

                    <h3>ZONDAG</h3>

                    <p>15 november 2026</p>

                    <p class="location">
                        Sneakerness® Rotterdam
                    </p>

                    @if ($gekozenDag === 'zondag')
                        <div class="check-badge">&#10003;</div>
                    @endif
                </a>
            </div>

            @if (!empty($gekozenDag) && empty($errorMessage))
                <div class="actions">
                    <a href="{{ route('tickets.index', ['dag' => $gekozenDag, 'stap' => 2]) }}" class="btn-volgende">
                        VOLGENDE STAP &rarr;
                    </a>
                </div>
            @endif
        @endif

        <!-- STAP 2: KIES JE TIJDSLOT -->
        @if ($stap === 2)
            <h2 class="section-title">
                KIES JE TIJDSLOT
            </h2>

            <p class="step-subtitle">
                {{ $gekozenDag === 'zaterdag' ? 'Zaterdag · 14 november 2026' : 'Zondag · 15 november 2026' }}
            </p>

            @php
                $huidigeTickets = $gekozenDag === 'zaterdag' ? $ticketsZaterdag : $ticketsZondag;
            @endphp

            <div class="timeslot-list">
                @forelse ($huidigeTickets as $ticket)
                    <!-- De bezoeker kiest een ticket uit de database -->
                    <a href="{{ route('tickets.index', [
                        'dag' => $gekozenDag,
                        'stap' => 3,
                        'ticket' => $ticket->id,
                    ]) }}"
                        class="timeslot-card timeslot-selecteerbaar">

                        <div class="timeslot-left">
                            <div class="timeslot-time">
                                {{ substr($ticket->tijdslot, 0, 5) }}
                            </div>

                            <div class="timeslot-info">
                                <span class="timeslot-label">
                                    INGANG
                                </span>

                                <span class="timeslot-available">
                                    Nog {{ $ticket->aantal_tickets_per_tijdslot }}
                                    tickets beschikbaar
                                </span>
                            </div>
                        </div>

                        <div>
                            <div class="timeslot-price">
                                €{{ number_format($ticket->tarief, 2, ',', '.') }}
                            </div>

                            <div class="timeslot-unit">
                                p.p.
                            </div>
                        </div>
                    </a>
                @empty
                    @if (empty($errorMessage))
                        <p class="empty-message">
                            Geen beschikbare tickets voor deze dag.
                        </p>
                    @endif
                @endforelse
            </div>

            <div class="actions has-back">
                <a href="{{ route('tickets.index', [
                    'dag' => $gekozenDag,
                    'stap' => 1,
                ]) }}"
                    class="btn-terug">
                    &larr; TERUG
                </a>
            </div>
        @endif

        <!-- STAP 3: VUL JE GEGEVENS IN -->
        @if ($stap === 3 && $gekozenTicket !== null)
            <h2 class="section-title">
                VUL JE GEGEVENS IN
            </h2>

            <p class="step-subtitle">
                {{ ucfirst($gekozenDag) }}
                · {{ \Carbon\Carbon::parse($gekozenTicket->datum)->format('d-m-Y') }}
                · {{ substr($gekozenTicket->tijdslot, 0, 5) }}
            </p>

            <!-- Gegevens worden pas na validatie tijdelijk opgeslagen -->
            <form method="POST" action="{{ route('tickets.gegevens') }}" class="ticket-formulier"
                id="ticketFormulier">

                @csrf

                <input type="hidden" name="ticket_id" value="{{ $gekozenTicket->id }}">

                <div class="formulier-groep">
                    <label for="naam">VOLLEDIGE NAAM</label>

                    <input type="text" id="naam" name="naam" value="{{ old('naam') }}" maxlength="100"
                        autocomplete="name" placeholder="Vul je volledige naam in" required>
                </div>

                <div class="formulier-groep">
                    <label for="email">E-MAILADRES</label>

                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        maxlength="255" autocomplete="email" placeholder="Vul je e-mailadres in" required>
                </div>

                <!-- Aantal tickets: minimaal 1, maximaal 10 -->
                <div class="formulier-groep">
                    <label for="aantal">AANTAL TICKETS</label>

                    <div class="aantal-bediening">
                        <button type="button" class="aantal-knop" onclick="veranderAantal(-1)"
                            aria-label="Minder tickets">
                            −
                        </button>

                        <input type="number" id="aantal" name="aantal" value="{{ old('aantal', 1) }}"
                            min="1" max="{{ min(10, (int) $gekozenTicket->aantal_tickets_per_tijdslot) }}"
                            required>

                        <button type="button" class="aantal-knop" onclick="veranderAantal(1)"
                            aria-label="Meer tickets">
                            +
                        </button>
                    </div>

                    <p class="formulier-hint">
                        Maximaal 10 tickets per bestelling
                    </p>
                </div>

                <!-- Betaalmethode kiezen -->
                <div class="formulier-groep">
                    <span class="formulier-label">BETAALMETHODE</span>

                    <div class="betaalmethoden">
                        <label class="betaalmethode-kaart">
                            <input type="radio" name="betaalmethode" value="ideal"
                                {{ old('betaalmethode', 'ideal') === 'ideal' ? 'checked' : '' }} required>

                            <span>iDEAL</span>
                        </label>

                        <label class="betaalmethode-kaart">
                            <input type="radio" name="betaalmethode" value="contant"
                                {{ old('betaalmethode') === 'contant' ? 'checked' : '' }}>

                            <span>Contant</span>
                        </label>
                    </div>
                </div>

                <!-- Totaalprijs wordt hier alleen getoond.
                     De controller berekent de prijs opnieuw vanuit de database. -->
                <div class="prijs-samenvatting">
                    <span>TOTAAL</span>

                    <strong id="totaalPrijs" data-prijs="{{ $gekozenTicket->tarief }}">
                        €{{ number_format($gekozenTicket->tarief, 2, ',', '.') }}
                    </strong>
                </div>

                <div class="actions has-back">
                    <a href="{{ route('tickets.index', [
                        'dag' => $gekozenDag,
                        'stap' => 2,
                    ]) }}"
                        class="btn-terug">
                        &larr; TERUG
                    </a>

                    <button type="submit" class="btn-volgende">
                        NAAR BESTELOVERZICHT &rarr;
                    </button>
                </div>
            </form>
        @endif

    </main>

    <!-- JavaScript voor navigatie en ticket-aantal -->
    <script>
        function toggleMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }

        // Verhoog of verlaag het aantal binnen de toegestane grenzen.
        function veranderAantal(verschil) {
            const aantalInvoer = document.getElementById('aantal');

            if (!aantalInvoer) {
                return;
            }

            const minimum = Number(aantalInvoer.min);
            const maximum = Number(aantalInvoer.max);
            const huidigAantal = Number(aantalInvoer.value) || minimum;

            aantalInvoer.value = Math.min(
                maximum,
                Math.max(minimum, huidigAantal + verschil)
            );

            werkTotaalprijsBij();
        }

        // Toon de geschatte totaalprijs.
        // De server controleert en berekent deze opnieuw.
        function werkTotaalprijsBij() {
            const aantalInvoer = document.getElementById('aantal');
            const totaalElement = document.getElementById('totaalPrijs');

            if (!aantalInvoer || !totaalElement) {
                return;
            }

            const prijsPerTicket = Number(totaalElement.dataset.prijs);
            const aantal = Number(aantalInvoer.value) || 0;
            const totaal = prijsPerTicket * aantal;

            totaalElement.textContent = new Intl.NumberFormat('nl-NL', {
                style: 'currency',
                currency: 'EUR'
            }).format(totaal);
        }

        document.getElementById('aantal')?.addEventListener(
            'input',
            werkTotaalprijsBij
        );

        werkTotaalprijsBij();
    </script>
</body>

</html>
