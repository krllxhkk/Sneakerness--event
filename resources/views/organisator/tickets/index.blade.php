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

    <!-- =====================================
         BOVENSTE NAVIGATIE
    ====================================== -->

    <header class="dashboard-header">

        <div class="dashboard-logo">
            SNEAKERNESS<span>®</span>
        </div>

        <!-- Hamburgermenu voor mobiel -->
        <button type="button" class="burger-menu" id="burgerMenu" aria-label="Open navigatiemenu"
            aria-controls="mobielMenu" aria-expanded="false">
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
        <form method="POST" action="{{ route('logout') }}" class="uitloggen-formulier">
            @csrf

            <button type="submit" class="uitloggen-knop">
                UITLOGGEN
            </button>
        </form>

    </header>

    <div class="dashboard-layout">

        <!-- =====================================
             ZIJBALK
        ====================================== -->

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

        <!-- =====================================
             HOOFDINHOUD
        ====================================== -->

        <main class="dashboard-inhoud">

            <!-- Titel en knop Ticket toevoegen -->
            <div class="tickets-kop">

                <div>
                    <h1>TICKETS</h1>

                    <p>
                        {{ $tickets->count() }} tickettypes totaal
                    </p>
                </div>

                <a href="{{ route('organisator.tickets.create') }}" class="toevoegen-knop">
                    TICKET TOEVOEGEN
                </a>

            </div>

            <!-- =====================================
                 SUCCESMELDING
            ====================================== -->

            @if (session('success'))
                <div class="ticket-melding" id="ticketSuccesMelding" role="status">
                    {{ session('success') }}
                </div>
            @endif

            <!-- =====================================
                 DATABASEFOUT
            ====================================== -->

            @if ($errorMessage)
                <p class="ticket-fout" role="alert">
                    {{ $errorMessage }}
                </p>
            @endif

            <!-- =====================================
                 FOUTMELDING
            ====================================== -->

            @if (session('error'))
                <div class="ticket-formulier-fout" id="ticketFoutMelding" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- =====================================
                 VALIDATIEFOUTEN
            ====================================== -->

            @if ($errors->any())
                <div class="ticket-formulier-fout" role="alert">

                    <strong>
                        Controleer de ingevulde gegevens:
                    </strong>

                    <ul>
                        @foreach ($errors->all() as $fout)
                            <li>{{ $fout }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif

            <!-- =====================================
                 ZOEKVELD
            ====================================== -->

            <input type="search" id="ticketsZoeken" class="zoekveld" placeholder="Zoek op ticket ID of datum..."
                aria-label="Zoek tickets">

            <!-- =====================================
                 TICKETOVERZICHT
            ====================================== -->

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
                            <th>Acties</th>
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

                                <!-- Aantal -->
                                <td>
                                    {{ $ticket->aantal_tickets_per_tijdslot }}
                                </td>

                                <!-- Prijs -->
                                <td class="ticket-prijs">
                                    €{{ number_format($ticket->tarief, 2, ',', '.') }}
                                </td>

                                <!-- Status -->
                                <td>
                                    <span
                                        class="ticket-status {{ $ticket->is_actief ? 'status-actief' : 'status-inactief' }}">
                                        {{ $ticket->is_actief ? 'Actief' : 'Inactief' }}
                                    </span>
                                </td>

                                <!-- =====================================
                                     KNOPPEN BEWERKEN EN ANNULEREN
                                ====================================== -->

                                <td>

                                    <!-- Bestaande UPDATE-knop -->
                                    <button type="button" class="ticket-bewerken-knop" data-id="{{ $ticket->id }}"
                                        data-datum="{{ \Carbon\Carbon::parse($ticket->datum)->format('Y-m-d') }}"
                                        data-tijdslot="{{ substr($ticket->tijdslot, 0, 5) }}"
                                        data-aantal="{{ $ticket->aantal_tickets_per_tijdslot }}"
                                        data-prijs="{{ $ticket->tarief }}"
                                        data-status="{{ $ticket->is_actief ? 1 : 0 }}">
                                        BEWERKEN
                                    </button>

                                    <!-- Nieuwe DELETE-knop -->

                                    <button type="button" class="ticket-verwijderen-knop"
                                        data-id="{{ $ticket->id }}"
                                        data-ticketnummer="TK-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}">
                                        VERWIJDEREN
                                    </button>


                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8">
                                    Er zijn nog geen tickets beschikbaar.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </main>

    </div>

    <!-- =====================================
         TICKET WIJZIGEN - BEWERKVENSTER
    ====================================== -->

    <div id="ticketBewerkModal" class="ticket-modal" role="dialog" aria-modal="true"
        aria-labelledby="ticketBewerkTitel" aria-hidden="true">

        <div class="ticket-modal-inhoud">

            <div class="ticket-modal-kop">

                <h2 id="ticketBewerkTitel">
                    TICKET BEWERKEN
                </h2>

                <button type="button" class="ticket-modal-sluiten" id="ticketModalSluiten"
                    aria-label="Sluit bewerkvenster">
                    &times;
                </button>

            </div>

            <!-- Formulier voor UPDATE -->
            <form id="ticketBewerkFormulier" class="ticket-bewerk-formulier" method="POST">

                @csrf
                @method('PUT')

                <div class="ticket-bewerk-grid">

                    <!-- Datum -->
                    <div class="ticket-bewerk-groep">

                        <label for="bewerkDatum">
                            Datum
                        </label>

                        <input type="date" id="bewerkDatum" name="datum" required>

                    </div>

                    <!-- Tijdslot -->
                    <div class="ticket-bewerk-groep">

                        <label for="bewerkTijdslot">
                            Tijdslot
                        </label>

                        <select id="bewerkTijdslot" name="tijdslot" required>
                            <option value="11:00">11:00</option>
                            <option value="12:00">12:00</option>
                            <option value="14:00">14:00</option>
                            <option value="16:00">16:00</option>
                        </select>

                    </div>

                    <!-- Aantal tickets -->
                    <div class="ticket-bewerk-groep">

                        <label for="bewerkAantal">
                            Aantal
                        </label>

                        <input type="number" id="bewerkAantal" name="aantal_tickets_per_tijdslot" min="1"
                            max="2147483647" step="1" required>

                    </div>

                    <!-- Prijs -->
                    <div class="ticket-bewerk-groep">

                        <label for="bewerkPrijs">
                            Prijs (€)
                        </label>

                        <input type="number" id="bewerkPrijs" name="tarief" min="0" max="999999.99"
                            step="0.01" required>

                    </div>

                    <!-- Status -->
                    <div class="ticket-bewerk-groep">

                        <label for="bewerkStatus">
                            Status
                        </label>

                        <select id="bewerkStatus" name="is_actief" required>
                            <option value="1">
                                Actief
                            </option>

                            <option value="0">
                                Inactief
                            </option>
                        </select>

                    </div>

                </div>

                <div class="ticket-bewerk-knoppen">

                    <!-- Sluit alleen het UPDATE-venster -->
                    <button type="button" class="ticket-bewerk-annuleren" id="ticketBewerkAnnuleren">
                        VERWIJDEREN
                    </button>

                    <button type="submit" class="ticket-bewerk-opslaan">
                        OPSLAAN
                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- =====================================
         TICKET ANNULEREN - BEVESTIGINGSVENSTER
    ====================================== -->

    <div id="ticketVerwijderModal" class="ticket-modal" role="dialog" aria-modal="true"
        aria-labelledby="ticketVerwijderTitel" aria-hidden="true">

        <div class="ticket-modal-inhoud">

            <div class="ticket-modal-kop">

                <h2 id="ticketVerwijderTitel">
                    TICKET ANNULEREN
                </h2>

                <button type="button" class="ticket-modal-sluiten" id="ticketVerwijderSluiten"
                    aria-label="Sluit annuleervenster">
                    &times;
                </button>

            </div>

            <p>
                Weet je zeker dat je ticket
                <strong id="ticketVerwijderNummer"></strong>
                wilt annuleren?
            </p>

            <p>
                Dit ticket wordt definitief uit de database verwijderd.
                Deze actie kan niet ongedaan worden gemaakt.
            </p>

            <!-- Formulier voor DELETE -->
            <form id="ticketVerwijderFormulier" method="POST">

                @csrf
                @method('DELETE')

                <div class="ticket-bewerk-knoppen">

                    <button type="button" class="ticket-bewerk-annuleren" id="ticketVerwijderTerug">
                        TERUG
                    </button>

                    <button type="submit" class="ticket-verwijderen-bevestigen">
                        BEVESTIGEN
                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- =====================================
         JAVASCRIPT
    ====================================== -->

    <script>
        // =====================================
        // TICKETS ZOEKEN
        // =====================================

        const zoekveld = document.getElementById('ticketsZoeken');
        const rijen = document.querySelectorAll('#ticketsRijen tr');

        zoekveld.addEventListener('input', function() {
            const zoektekst = this.value.toLowerCase();

            rijen.forEach(function(rij) {
                const ticketgegevens = rij.textContent.toLowerCase();

                rij.style.display = ticketgegevens.includes(zoektekst) ?
                    '' :
                    'none';
            });
        });

        // =====================================
        // HAMBURGERMENU
        // =====================================

        const burgerMenu = document.getElementById('burgerMenu');
        const mobielMenu = document.getElementById('mobielMenu');

        burgerMenu.addEventListener('click', function() {
            const isOpen = mobielMenu.classList.toggle('menu-open');

            burgerMenu.setAttribute('aria-expanded', String(isOpen));

            burgerMenu.setAttribute(
                'aria-label',
                isOpen ? 'Sluit navigatiemenu' : 'Open navigatiemenu'
            );
        });

        // =====================================
        // TICKET WIJZIGEN - ELEMENTEN
        // =====================================

        const ticketBewerkModal = document.getElementById('ticketBewerkModal');
        const ticketBewerkFormulier = document.getElementById('ticketBewerkFormulier');
        const ticketModalSluiten = document.getElementById('ticketModalSluiten');
        const ticketBewerkAnnuleren = document.getElementById('ticketBewerkAnnuleren');
        const bewerkKnoppen = document.querySelectorAll('.ticket-bewerken-knop');

        let vorigeFocus = null;

        // =====================================
        // UPDATE-ROUTE VAN LARAVEL
        // =====================================

        const ticketUpdateRoute = @json(route('organisator.tickets.update', ['id' => '__TICKET_ID__']));

        // =====================================
        // BEWERKVENSTER OPENEN
        // =====================================

        function openTicketBewerkModal(knop) {
            vorigeFocus = document.activeElement;

            document.getElementById('bewerkDatum').value = knop.dataset.datum;
            document.getElementById('bewerkTijdslot').value = knop.dataset.tijdslot;
            document.getElementById('bewerkAantal').value = knop.dataset.aantal;
            document.getElementById('bewerkPrijs').value = knop.dataset.prijs;
            document.getElementById('bewerkStatus').value = knop.dataset.status;

            ticketBewerkFormulier.action = ticketUpdateRoute.replace(
                '__TICKET_ID__',
                encodeURIComponent(knop.dataset.id)
            );

            ticketBewerkModal.classList.add('is-open');
            ticketBewerkModal.setAttribute('aria-hidden', 'false');

            document.body.style.overflow = 'hidden';
            ticketModalSluiten.focus();
        }

        // =====================================
        // BEWERKVENSTER SLUITEN
        // =====================================

        function sluitTicketBewerkModal() {
            ticketBewerkModal.classList.remove('is-open');
            ticketBewerkModal.setAttribute('aria-hidden', 'true');

            document.body.style.overflow = '';

            if (vorigeFocus) {
                vorigeFocus.focus();
            }
        }

        // =====================================
        // KNOP BEWERKEN
        // =====================================

        bewerkKnoppen.forEach(function(knop) {
            knop.addEventListener('click', function() {
                openTicketBewerkModal(knop);
            });
        });

        ticketModalSluiten.addEventListener(
            'click',
            sluitTicketBewerkModal
        );

        ticketBewerkAnnuleren.addEventListener(
            'click',
            sluitTicketBewerkModal
        );

        ticketBewerkModal.addEventListener('click', function(event) {
            if (event.target === ticketBewerkModal) {
                sluitTicketBewerkModal();
            }
        });

        // =====================================
        // TICKET ANNULEREN - ELEMENTEN
        // =====================================

        const ticketVerwijderModal = document.getElementById('ticketVerwijderModal');
        const ticketVerwijderFormulier = document.getElementById('ticketVerwijderFormulier');
        const ticketVerwijderSluiten = document.getElementById('ticketVerwijderSluiten');
        const ticketVerwijderTerug = document.getElementById('ticketVerwijderTerug');
        const ticketVerwijderNummer = document.getElementById('ticketVerwijderNummer');

        const verwijderKnoppen = document.querySelectorAll('.ticket-verwijderen-knop');

        const ticketDeleteRoute = @json(route('organisator.tickets.destroy', ['id' => '__TICKET_ID__']));

        let vorigeVerwijderFocus = null;

        // =====================================
        // BEVESTIGINGSVENSTER OPENEN
        // =====================================

        function openTicketVerwijderModal(knop) {
            vorigeVerwijderFocus = document.activeElement;

            // Toon het juiste ticketnummer.
            ticketVerwijderNummer.textContent = knop.dataset.ticketnummer;

            // Stel de DELETE-route in voor het gekozen ticket.
            ticketVerwijderFormulier.action = ticketDeleteRoute.replace(
                '__TICKET_ID__',
                encodeURIComponent(knop.dataset.id)
            );

            ticketVerwijderModal.classList.add('is-open');
            ticketVerwijderModal.setAttribute('aria-hidden', 'false');

            document.body.style.overflow = 'hidden';
            ticketVerwijderSluiten.focus();
        }

        // =====================================
        // BEVESTIGINGSVENSTER SLUITEN
        // =====================================

        function sluitTicketVerwijderModal() {
            ticketVerwijderModal.classList.remove('is-open');
            ticketVerwijderModal.setAttribute('aria-hidden', 'true');

            document.body.style.overflow = '';

            if (vorigeVerwijderFocus) {
                vorigeVerwijderFocus.focus();
            }
        }

        // =====================================
        // KNOP ANNULEREN IN TICKETOVERZICHT
        // =====================================

        verwijderKnoppen.forEach(function(knop) {
            knop.addEventListener('click', function() {
                openTicketVerwijderModal(knop);
            });
        });

        // =====================================
        // KNOPPEN SLUITEN EN TERUG
        // =====================================

        ticketVerwijderSluiten.addEventListener(
            'click',
            sluitTicketVerwijderModal
        );

        ticketVerwijderTerug.addEventListener(
            'click',
            sluitTicketVerwijderModal
        );

        // =====================================
        // BUITEN HET VENSTER KLIKKEN
        // =====================================

        ticketVerwijderModal.addEventListener('click', function(event) {
            if (event.target === ticketVerwijderModal) {
                sluitTicketVerwijderModal();
            }
        });

        // =====================================
        // SLUITEN MET ESCAPE
        // =====================================

        document.addEventListener('keydown', function(event) {
            if (event.key !== 'Escape') {
                return;
            }

            if (ticketBewerkModal.classList.contains('is-open')) {
                sluitTicketBewerkModal();
            }

            if (ticketVerwijderModal.classList.contains('is-open')) {
                sluitTicketVerwijderModal();
            }
        });

        // =====================================
        // MELDINGEN NA 3 SECONDEN VERBERGEN
        // =====================================

        const ticketSuccesMelding = document.getElementById('ticketSuccesMelding');
        const ticketFoutMelding = document.getElementById('ticketFoutMelding');

        const meldingen = [
            ticketSuccesMelding,
            ticketFoutMelding
        ];

        meldingen.forEach(function(melding) {
            if (melding) {
                setTimeout(function() {
                    melding.style.display = 'none';
                }, 3000);
            }
        });

        // =====================================
        // FORMULIEREN VERZENDEN
        // =====================================

        // UPDATE gebruikt @method('PUT').
        // DELETE gebruikt @method('DELETE').
        // Laravel verwerkt beide formulieren via de juiste route.
    </script>

</body>

</html>
