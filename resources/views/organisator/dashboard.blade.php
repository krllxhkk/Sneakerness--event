
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Organisator Dashboard | Sneakerness®</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>

    <header class="dashboard-header">

        <div class="dashboard-logo">
            SNEAKERNESS<span>®</span>
        </div>

        <nav class="dashboard-nav">

            <a href="{{ route('organisator.dashboard') }}" class="actief">
                Dashboard
            </a>

            <a href="{{ route('events.index') }}">
                Evenementen
            </a>

            <a href="{{ route('organisator.tickets.index') }}">
                Tickets
            </a>

            <a href="{{ route('verkopers.index') }}">
                Verkopers
            </a>

            <a href="{{ route('stands.index') }}">
                Stands
            </a>

        </nav>

        <form method="POST"
              action="{{ route('logout') }}"
              class="uitloggen-formulier">

            @csrf

            <button type="submit" class="uitloggen-knop">
                UITLOGGEN
            </button>

        </form>

    </header>

    <div class="dashboard-layout">

        <!-- Beheer Panel -->
        <aside class="zijbalk">

            <p class="zijbalk-label">
                Organisator
            </p>

            <h2>Beheer Panel</h2>

            <nav class="beheer-menu">

                <a href="{{ route('organisator.dashboard') }}" class="actief">
                    Dashboard
                </a>

                <a href="{{ route('events.index') }}">
                    Evenementen
                </a>

                <a href="{{ route('organisator.tickets.index') }}">
                    Tickets
                </a>

                <a href="{{ route('verkopers.index') }}">
                    Verkopers
                </a>

                <a href="{{ route('stands.index') }}">
                    Stands
                </a>

            </nav>

        </aside>

        <!-- Dashboard inhoud -->
        <main class="dashboard-inhoud">

            <div class="dashboard-kop">

                <p>Organisator Dashboard</p>

                <h1>Welkom bij Sneakerness®</h1>

                <span>
                    Beheer het evenement vanuit het Beheer Panel.
                </span>

            </div>

            <!-- Statistieken -->
            <section class="statistieken">

                <div class="statistiek-kaart">
                    <p>Totaal tickets</p>
                    <h3>{{ $totaalTickets }}</h3>
                    <span class="kaart-icoon">🎟️</span>
                </div>

                <div class="statistiek-kaart">
                    <p>Verkochte tickets</p>
                    <h3>{{ $verkochteTickets }}</h3>
                    <span class="kaart-icoon">✓</span>
                </div>

                <div class="statistiek-kaart">
                    <p>Beschikbare tickets</p>
                    <h3>{{ $beschikbareTickets }}</h3>
                    <span class="kaart-icoon">🎫</span>
                </div>

                <div class="statistiek-kaart">
                    <p>Totale omzet</p>
                    <h3>€{{ number_format($totaleOmzet, 2, ',', '.') }}</h3>
                    <span class="kaart-icoon">💰</span>
                </div>

                <div class="statistiek-kaart">
                    <p>Totaal stands</p>
                    <h3>{{ $totaalStands }}</h3>
                    <span class="kaart-icoon">🏬</span>
                </div>

                <div class="statistiek-kaart">
                    <p>Verhuurde stands</p>
                    <h3>{{ $verhuurdeStands }}</h3>
                    <span class="kaart-icoon">✓</span>
                </div>

                <div class="statistiek-kaart">
                    <p>Beschikbare stands</p>
                    <h3>{{ $beschikbareStands }}</h3>
                    <span class="kaart-icoon">◻</span>
                </div>

                <div class="statistiek-kaart">
                    <p>Bezoekers</p>
                    <h3>{{ $aantalBezoekers }}</h3>
                    <span class="kaart-icoon">👥</span>
                </div>

            </section>

            <!-- Dashboard blokken -->
            <section class="dashboard-blokken">

                <div class="dashboard-blok tickets-tijdsloten">

                    <h2>Tickets per tijdslot</h2>

                    @foreach ($tickets as $ticket)

                        <div class="tijdslot-rij">

                            <span class="tijdslot-naam">
                                {{ \Carbon\Carbon::parse($ticket->datum)->format('d-m') }}
                                {{ \Carbon\Carbon::parse($ticket->tijdslot)->format('H:i') }}
                            </span>

                            <div class="tijdslot-balk-achtergrond">

                                <div class="tijdslot-balk"
                                     style="width: {{ ($ticket->aantal_tickets_per_tijdslot / 410) * 100 }}%;">
                                </div>

                            </div>

                            <span class="tijdslot-aantal">
                                {{ $ticket->aantal_tickets_per_tijdslot }}
                            </span>

                        </div>

                    @endforeach

                </div>

                <div class="dashboard-blok">

                    <h2>Verhuurde stands per type</h2>

                    <p class="geen-gegevens">
                        Er zijn nog geen standgegevens beschikbaar.
                    </p>

                </div>

            </section>

            <!-- Recente ticketbestellingen -->
            <section class="recente-sectie">

                <div class="sectie-kop">

                    <h2>Recente ticketbestellingen</h2>

                    <a href="{{ route('organisator.tickets.index') }}">
                        Alle tickets →
                    </a>

                </div>

                <div class="dashboard-tabel">

                    <table>

                        <thead>
                            <tr>
                                <th>Naam</th>
                                <th>E-mail</th>
                                <th>Dag</th>
                                <th>Tijdslot</th>
                                <th>Aantal</th>
                                <th>Prijs</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td colspan="7" class="lege-tabel">
                                    Er zijn nog geen ticketbestellingen.
                                </td>
                            </tr>
                        </tbody>

                    </table>

                </div>

            </section>

        </main>

    </div>

</body>

</html>
