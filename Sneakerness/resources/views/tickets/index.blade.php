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
            <a href="{{ route('home') }}" class="logo">SNEAKERNESS<sup>®</sup></a>

            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('events.index') }}"
                        class="{{ request()->routeIs('events.index') ? 'active' : '' }}">Event</a></li>
                <li><a href="{{ route('tickets.index') }}"
                        class="{{ request()->routeIs('tickets.index') ? 'active' : '' }}">Tickets</a></li>
                <li><a href="{{ route('verkopers.index') }}"
                        class="{{ request()->routeIs('verkopers.index') ? 'active' : '' }}">Verkopers</a></li>
                <li><a href="{{ route('stands.index') }}"
                        class="{{ request()->routeIs('stands.index') ? 'active' : '' }}">Stands</a></li>
                <li><a href="{{ route('contactpersonen.index') }}"
                        class="{{ request()->routeIs('contactpersonen.index') ? 'active' : '' }}">Contact</a></li>
            </ul>
            <div class="nav-right">
                <a href="#" class="login-link">LOGIN</a>
                <a href="{{ route('tickets.index') }}" class="btn-yellow-sm">TICKET KOPEN</a>
            </div>
        </div>
    </header>

    <main class="main-container">

        @if (!empty($errorMessage))
            <div class="error-alert">{{ $errorMessage }}</div>
        @endif

        <div class="page-header">
            <span class="page-subtitle">SNEAKERNESS® ROTTERDAM 2026</span>
            <h1 class="page-title">TICKETS KOPEN</h1>
        </div>

        <!-- STEPPER -->
        <div class="stepper">
            <div class="step-item">
                <div class="step-circle {{ $stap === 1 ? 'active' : 'completed' }}">
                    {!! $stap > 1 ? '&#10003;' : '1' !!}
                </div>
                <span class="step-label {{ $stap === 1 ? 'active' : '' }}">Dag</span>
            </div>
            <div class="step-item">
                <div class="step-circle {{ $stap === 2 ? 'active' : '' }}">2</div>
                <span class="step-label {{ $stap === 2 ? 'active' : '' }}">Tijdslot</span>
            </div>
            <div class="step-item">
                <div class="step-circle">3</div>
                <span class="step-label">Gegevens</span>
            </div>
            <div class="step-item">
                <div class="step-circle">4</div>
                <span class="step-label">Overzicht</span>
            </div>
        </div>

        <!-- STAP 1: KIES JE DAG -->
        @if ($stap === 1)
            <h2 class="section-title">KIES JE DAG</h2>

            <div class="day-grid">
                <!-- Zaterdag Kaart -->
                <a href="{{ route('tickets.index', ['dag' => 'zaterdag', 'stap' => 1]) }}"
                    class="day-card {{ $gekozenDag === 'zaterdag' ? 'selected' : '' }}">
                    <div class="icon">📅</div>
                    <h3>ZATERDAG</h3>
                    <p>14 november 2026</p>
                    <p class="location">Sneakerness® Rotterdam</p>
                    @if ($gekozenDag === 'zaterdag')
                        <div class="check-badge">&#10003;</div>
                    @endif
                </a>

                <!-- Zondag Kaart -->
                <a href="{{ route('tickets.index', ['dag' => 'zondag', 'stap' => 1]) }}"
                    class="day-card {{ $gekozenDag === 'zondag' ? 'selected' : '' }}">
                    <div class="icon">☀️</div>
                    <h3>ZONDAG</h3>
                    <p>15 november 2026</p>
                    <p class="location">Sneakerness® Rotterdam</p>
                    @if ($gekozenDag === 'zondag')
                        <div class="check-badge">&#10003;</div>
                    @endif
                </a>
            </div>

            @if (!empty($gekozenDag))
                <div class="actions">
                    <a href="{{ route('tickets.index', ['dag' => $gekozenDag, 'stap' => 2]) }}"
                        class="btn-volgende">VOLGENDE STAP &rarr;</a>
                </div>
            @endif
        @endif

        <!-- STAP 2: KIES JE TIJDSLOT -->
        @if ($stap === 2)
            <h2 class="section-title">KIES JE TIJDSLOT</h2>
            <p class="step-subtitle">
                {{ $gekozenDag === 'zaterdag' ? 'Zaterdag · 14 November 2026' : 'Zondag · 15 November 2026' }}
            </p>

            <div class="timeslot-list">
                @php
                    $huidigeTickets = $gekozenDag === 'zaterdag' ? $ticketsZaterdag : $ticketsZondag;
                @endphp

                @forelse ($huidigeTickets as $ticket)
                    <div class="timeslot-card">
                        <div class="timeslot-left">
                            <div class="timeslot-time">
                                {{ substr($ticket->tijdslot, 0, 5) }}
                            </div>
                            <div class="timeslot-info">
                                <span class="timeslot-label">INGANG</span>
                                <span class="timeslot-available">
                                    Nog {{ $ticket->aantal_tickets_per_tijdslot }} tickets beschikbaar
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="timeslot-price">
                                €{{ number_format($ticket->tarief, 0) }}
                            </div>
                            <div class="timeslot-unit">p.p.</div>
                        </div>
                    </div>
                @empty
                    @if (empty($errorMessage))
                        <p class="empty-message">Geen beschikbare tickets voor deze dag.</p>
                    @endif
                @endforelse
            </div>

            <div class="actions has-back">
                <a href="{{ route('tickets.index', ['dag' => $gekozenDag, 'stap' => 1]) }}" class="btn-terug">&larr;
                    TERUG</a>
                <button class="btn-volgende">VOLGENDE STAP &rarr;</button>
            </div>
        @endif

    </main>

</body>

</html>
