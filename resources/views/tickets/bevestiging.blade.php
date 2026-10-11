<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ticket besteld! | Sneakerness®</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bevestiging.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mailbox.css') }}">
</head>

<body>

    <!-- NAVIGATIE VAN DE HOMEPAGINA -->
    <header>
        <div class="header-container">

            <a href="{{ route('home') }}" class="logo" style="text-decoration: none;">
                SNEAKERNESS<sup>®</sup>
            </a>

            <button type="button" class="hamburger" id="hamburgerBtn" onclick="toggleMenu()" aria-label="Open menu"
                aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <ul class="nav-links" id="navLinks">
                <li>
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li>
                    <a href="{{ route('events.index') }}">Events</a>
                </li>
                <li>
                    <a href="{{ route('tickets.index') }}" class="active">
                        Tickets
                    </a>
                </li>
                <li>
                    <a href="{{ route('verkopers.index') }}">Verkopers</a>
                </li>
                <li>
                    <a href="{{ route('stands.index') }}">Stands</a>
                </li>
            </ul>

            <div class="nav-right">

                @include('components.ticket-mailbox')

                @guest
                    <a href="{{ route('login') }}" class="login-link">
                        LOGIN
                    </a>
                    <a href="{{ route('register') }}" class="login-link">
                        REGISTREREN
                    </a>
                @endguest

                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="login-link" style="background: none; border: none; cursor: pointer;">
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

    <!-- FIGMA BEVESTIGING -->
    <main class="bevestiging-pagina">
        <div class="figma-bevestiging">

            <div class="figma-vinkje">
                ✓
            </div>

            <h1>TICKET BESTELD!</h1>

            <p class="figma-omschrijving">
                Bevestiging is verstuurd naar
                {{ $bestelling['email'] }}
            </p>

            <!-- TICKET -->
            <section class="figma-ticket">

                <div class="figma-ticket-header">
                    <h2>SNEAKERNESS<sup>®</sup></h2>
                    <p>Rotterdam · Van Nellefabriek</p>
                </div>

                <div class="figma-ticket-inhoud">

                    <div class="figma-rij">
                        <span>Ticket ID</span>
                        <strong>
                            {{ $bestelling['ticketnummers'][0] ?? '—' }}
                        </strong>
                    </div>

                    <div class="figma-rij">
                        <span>Naam</span>
                        <strong>{{ $bestelling['naam'] }}</strong>
                    </div>

                    <div class="figma-rij">
                        <span>Dag</span>
                        <strong>{{ $bestelling['dag'] }}</strong>
                    </div>

                    <div class="figma-rij">
                        <span>Tijdslot</span>
                        <strong>{{ $bestelling['tijdslot'] }}</strong>
                    </div>

                    <div class="figma-rij">
                        <span>Aantal</span>
                        <strong>{{ $bestelling['aantal'] }}×</strong>
                    </div>

                    <div class="figma-rij figma-totaal">
                        <span>Totaal</span>
                        <strong>
                            €{{ number_format($bestelling['totaalprijs'], 2, ',', '.') }}
                        </strong>
                    </div>

                    <!-- Ticketnummers blijven beschikbaar.
                         Bij meerdere tickets tonen we ze allemaal. -->
                    @if (count($bestelling['ticketnummers']) > 1)
                        <div class="figma-extra-tickets">
                            <span>Overige ticket-ID's</span>

                            @foreach (array_slice($bestelling['ticketnummers'], 1) as $nummer)
                                <p>{{ $nummer }}</p>
                            @endforeach
                        </div>
                    @endif

                </div>

                <!-- Decoratief symbool zoals in Figma.
                     Dit is geen scanbare QR-code. -->
                <div class="figma-qr-gedeelte">
                    <div class="figma-qr-symbool" aria-hidden="true">
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                    </div>
                </div>

            </section>

            <!-- KNOPPEN ONDER ELKAAR -->
            <div class="figma-knoppen">
                <a href="{{ route('home') }}" class="figma-knop-geel">
                    TERUG NAAR HOME
                </a>

                <a href="{{ route('tickets.index') }}" class="figma-knop-outline">
                    NOG EEN TICKET
                </a>
            </div>

        </div>
    </main>

    <script>
        // Mobiel navigatiemenu.
        function toggleMenu() {
            const menu = document.getElementById('navLinks');
            const knop = document.getElementById('hamburgerBtn');

            if (!menu) return;

            const open = menu.classList.toggle('active');

            if (knop) {
                knop.setAttribute('aria-expanded', String(open));
            }
        }

        // Mailbox openen en sluiten.
        document.addEventListener('DOMContentLoaded', function() {
            const mailbox = document.querySelector('[data-ticket-mailbox]');

            if (!mailbox) return;

            const knop = mailbox.querySelector('[data-mailbox-toggle]');
            const paneel = mailbox.querySelector('[data-mailbox-panel]');

            if (!knop || !paneel) return;

            knop.addEventListener('click', function() {
                paneel.hidden = !paneel.hidden;
                knop.setAttribute('aria-expanded', String(!paneel.hidden));
            });

            document.addEventListener('click', function(event) {
                if (!mailbox.contains(event.target)) {
                    paneel.hidden = true;
                    knop.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    paneel.hidden = true;
                    knop.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>

</body>

</html>
