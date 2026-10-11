<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Besteloverzicht | Sneakerness® Rotterdam 2026</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Inter:wght@400;600;700;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/tickets.css') }}">
</head>

<body>
    <!-- Bestaande navigatie van Sneakerness -->
    <header>
        <div class="header-container">
            <a href="{{ route('home') }}" class="logo">
                SNEAKERNESS<sup>®</sup>
            </a>

            <div class="hamburger" id="hamburgerBtn" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <ul class="nav-links" id="navLinks">
                <li>
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li>
                    <a href="{{ route('events.index') }}">Event</a>
                </li>
                <li>
                    <a href="{{ route('tickets.index') }}" class="active">Tickets</a>
                </li>
                <li>
                    <a href="{{ route('verkopers.index') }}">Verkopers</a>
                </li>
                <li>
                    <a href="{{ route('stands.index') }}">Stands</a>
                </li>
            </ul>

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

                <a href="{{ route('tickets.index') }}" class="btn-yellow-sm">
                    TICKET KOPEN
                </a>
            </div>
        </div>
    </header>

    <main class="main-container">

        <!-- Foutmelding helemaal bovenaan -->
        @if (session('error'))
            <div class="ticket-error-alert" role="alert">
                {{ session('error') }}
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

        <!-- Stap 4 is actief -->
        <div class="stepper">
            @foreach (['Dag', 'Tijdslot', 'Gegevens', 'Overzicht'] as $nummer => $naam)
                @php
                    $stapNummer = $nummer + 1;
                @endphp

                <div class="step-item">
                    <div class="step-circle {{ $stapNummer === 4 ? 'active' : 'completed' }}">
                        @if ($stapNummer < 4)
                            &#10003;
                        @else
                            4
                        @endif
                    </div>

                    <span class="step-label {{ $stapNummer === 4 ? 'active' : '' }}">
                        {{ $naam }}
                    </span>
                </div>
            @endforeach
        </div>

        <h2 class="section-title">
            CONTROLEER JE BESTELLING
        </h2>

        <p class="step-subtitle">
            Controleer je gegevens voordat je verdergaat.
        </p>

        <!-- Besteloverzicht uit de sessie -->
        <section class="besteloverzicht-kaart">
            <h3 class="besteloverzicht-titel">
                JOUW BESTELLING
            </h3>

            <div class="besteloverzicht-regel">
                <span>Evenement</span>
                <strong>Sneakerness® Rotterdam 2026</strong>
            </div>

            <div class="besteloverzicht-regel">
                <span>Dag</span>
                <strong>{{ ucfirst($besteloverzicht['dag']) }}</strong>
            </div>

            <div class="besteloverzicht-regel">
                <span>Datum</span>
                <strong>
                    {{ \Carbon\Carbon::parse($besteloverzicht['datum'])->format('d-m-Y') }}
                </strong>
            </div>

            <div class="besteloverzicht-regel">
                <span>Tijdslot</span>
                <strong>{{ $besteloverzicht['tijdslot'] }}</strong>
            </div>

            <div class="besteloverzicht-regel">
                <span>Naam</span>
                <strong>{{ $besteloverzicht['naam'] }}</strong>
            </div>

            <div class="besteloverzicht-regel">
                <span>E-mailadres</span>
                <strong>{{ $besteloverzicht['email'] }}</strong>
            </div>

            <div class="besteloverzicht-regel">
                <span>Aantal tickets</span>
                <strong>{{ $besteloverzicht['aantal'] }}</strong>
            </div>

            <div class="besteloverzicht-regel">
                <span>Prijs per ticket</span>
                <strong>
                    €{{ number_format($besteloverzicht['prijs_per_ticket'], 2, ',', '.') }}
                </strong>
            </div>

            <div class="besteloverzicht-regel">
                <span>Betaalmethode</span>
                <strong>
                    {{ $besteloverzicht['betaalmethode'] === 'ideal' ? 'iDEAL' : 'Contant' }}
                </strong>
            </div>

            <div class="besteloverzicht-totaal">
                <span>TOTAAL</span>
                <strong>
                    €{{ number_format($besteloverzicht['totaalprijs'], 2, ',', '.') }}
                </strong>
            </div>
        </section>

        <div class="actions has-back besteloverzicht-acties">
            <!-- Terug naar gegevens -->
            <a href="{{ route('tickets.index', [
                'dag' => $besteloverzicht['dag'],
                'stap' => 3,
                'ticket' => $besteloverzicht['ticket_id'],
            ]) }}"
                class="btn-terug">
                &larr; TERUG
            </a>

            <!-- Verstuur de bestelling -->
            <form method="POST" action="{{ route('tickets.bestellen') }}">
                @csrf

                <button type="submit" class="btn-volgende">
                    TICKET KOPEN
                </button>
            </form>
        </div>

        <p class="besteloverzicht-melding">
            Controleer je bestelling en klik op TICKET KOPEN om je bestelling af te ronden.
        </p>
    </main>

    <script>
        function toggleMenu() {
            document.getElementById('navLinks').classList.toggle('active');
        }
    </script>
</body>

</html>
