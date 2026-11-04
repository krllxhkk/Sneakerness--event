<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evenementen beheren | Sneakerness®</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>

    <!-- Bovenste navigatie -->
    <header class="dashboard-header">
        <div class="dashboard-logo">
            SNEAKERNESS<span>®</span>
        </div>

        <button type="button" class="burger-menu" aria-label="Menu openen" aria-expanded="false"
            aria-controls="dashboardNav" onclick="toggleMenu(this)">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="dashboard-nav" id="dashboardNav">
            <a href="{{ route('organisator.dashboard') }}">Dashboard</a>
            <a href="{{ route('organisator.events') }}" class="actief">Evenementen</a>
            <a href="{{ route('organisator.tickets.index') }}">Tickets</a>
            <a href="{{ route('organisator.verkopers') }}">Verkopers</a>
            <a href="{{ route('organisator.stands') }}">Stands</a>
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="uitloggen-formulier">
            @csrf
            <button type="submit" class="uitloggen-knop">UITLOGGEN</button>
        </form>
    </header>

    <div class="dashboard-layout">

        <!-- Linker beheermenu -->
        <aside class="zijbalk">
            <p class="zijbalk-label">Organisator</p>
            <h2>Beheer Panel</h2>

            <nav class="beheer-menu">
                <a href="{{ route('organisator.dashboard') }}">Dashboard</a>
                <a href="{{ route('organisator.events') }}" class="actief">Evenementen</a>
                <a href="{{ route('organisator.tickets.index') }}">Tickets</a>
                <a href="{{ route('organisator.verkopers') }}">Verkopers</a>
                <a href="{{ route('organisator.stands') }}">Stands</a>
            </nav>
        </aside>

        <main class="dashboard-inhoud">

            <!-- Titel en knop -->
            <div class="beheer-kop">
                <div>
                    <h1>EVENEMENTEN</h1>
                    <p>{{ $events->count() }} evenementen totaal</p>
                </div>

                <button type="button" class="toevoegen-knop" onclick="openEventModal()">
                    EVENT TOEVOEGEN
                </button>
            </div>

            <!-- Succes- en foutmeldingen -->
            @if (session('success'))
                <div class="melding-succes" role="status">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Foutmeldingen voor het verwijderen van events. -->
            <div id="eventVerwijderenFout" class="melding-fout" role="alert" tabindex="-1" @if (!$errors->eventVerwijderen->any()) hidden @endif>
                @foreach ($errors->eventVerwijderen->all() as $melding)
                    <p>{{ $melding }}</p>
                @endforeach
            </div>

            @if ($errors->any())
                <div class="melding-fout" role="alert">
                    Controleer de ingevulde gegevens.
                </div>
            @endif

            <!-- Tabel met evenementen -->
            <div class="beheer-tabel">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Naam</th>
                            <th>Datum</th>
                            <th>Tijd</th>
                            <th>Locatie</th>
                            <th>Tickets per tijdslot</th>
                            <th>Beschikbare stands</th>
                            <th>Acties</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($events as $event)
                                                <tr>
                                                    <td>EV-{{ str_pad($event->Id, 3, '0', STR_PAD_LEFT) }}</td>
                                                    <td class="beheer-naam">{{ $event->Naam }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($event->Datum)->format('d-m-Y') }}</td>
                                                    <td>{{ substr($event->Tijd ?? '', 0, 5) }}</td>
                                                    <td>{{ $event->Locatie }}</td>
                                                    <td>{{ $event->AantalTicketsPerTijdslot }}</td>
                                                    <td>{{ $event->BeschikbareStands }}</td>
                                                    <td>
                                                        <button type="button" class="opslaan-knop" data-event="{{ json_encode([
                                'Id' => $event->Id,
                                'Naam' => $event->Naam,
                                'Datum' => \Carbon\Carbon::parse($event->Datum)->format('Y-m-d'),
                                'Tijd' => substr($event->Tijd ?? '', 0, 5),
                                'Locatie' => $event->Locatie,
                            ]) }}" data-update-url="{{ route('events.update', ['event' => $event->Id]) }}"
                                                            onclick="openWijzigEventModal(this)">
                                                            WIJZIGEN
                                                        </button>
                                                        <button type="button" class="verwijderen-knop"
                                                            data-verwijder-url="{{ route('events.destroy', ['event' => $event->Id]) }}"
                                                            data-vandaag="{{ \Carbon\Carbon::parse($event->Datum)->toDateString() === \Carbon\Carbon::now('Europe/Amsterdam')->toDateString() ? '1' : '0' }}"
                                                            onclick="openVerwijderEventModal(this)">
                                                            VERWIJDEREN
                                                        </button>
                                                    </td>
                                                </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="lege-tabel">
                                    Er zijn nog geen evenementen beschikbaar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </main>
    </div>

    <!-- Popup voor een evenement toevoegen -->
    <div class="modal {{ $errors->any() ? 'actief' : '' }}" id="eventModal" role="dialog" aria-modal="true"
        aria-labelledby="eventModalTitel">

        <div class="modal-inhoud">
            <div class="modal-kop">
                <div>
                    <p>Evenementen beheren</p>
                    <h2 id="eventModalTitel">Event toevoegen</h2>
                </div>

                <button type="button" class="modal-sluiten" onclick="closeEventModal()" aria-label="Sluiten">
                    &times;
                </button>
            </div>

            <form action="{{ route('events.store') }}" method="POST">
                @csrf

                <div class="form-groep">
                    <label for="eventNaam">Naam</label>
                    <input type="text" id="eventNaam" name="Naam" value="{{ old('Naam') }}" maxlength="100" required>
                    @error('Naam')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="eventDatum">Datum</label>
                    <input type="date" id="eventDatum" name="Datum" min="{{ date('Y-m-d') }}" value="{{ old('Datum') }}"
                        required>
                    @error('Datum')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="eventTijd">Tijd</label>
                    <input type="time" id="eventTijd" name="Tijd" value="{{ old('Tijd') }}" required>
                    @error('Tijd')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="eventLocatie">Locatie</label>
                    <input type="text" id="eventLocatie" name="Locatie" value="{{ old('Locatie') }}" maxlength="150"
                        required>
                    @error('Locatie')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="eventTickets">Tickets per tijdslot</label>
                    <input type="number" id="eventTickets" name="AantalTicketsPerTijdslot" min="0"
                        value="{{ old('AantalTicketsPerTijdslot', 0) }}" required>
                    @error('AantalTicketsPerTijdslot')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="eventStands">Beschikbare stands</label>
                    <input type="number" id="eventStands" name="BeschikbareStands" min="0"
                        value="{{ old('BeschikbareStands', 0) }}" required>
                    @error('BeschikbareStands')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="modal-knoppen">
                    <button type="button" class="annuleer-knop" onclick="closeEventModal()">
                        ANNULEREN
                    </button>

                    <button type="submit" class="opslaan-knop">
                        OPSLAAN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pop-up voor het wijzigen van een bestaand evenement. -->
    <div class="modal {{ $errors->eventWijzigen->any() ? 'actief' : '' }}" id="wijzigEventModal" role="dialog"
        aria-modal="true" aria-labelledby="wijzigEventTitel">
        <div class="modal-inhoud">
            <div class="modal-kop">
                <div>
                    <p>Evenementen beheren</p>
                    <h2 id="wijzigEventTitel">Event wijzigen</h2>
                </div>

                <button type="button" class="modal-sluiten" onclick="closeWijzigEventModal()" aria-label="Sluiten">
                    &times;
                </button>
            </div>

            @if ($errors->eventWijzigen->any())
                <div class="melding-fout" role="alert">
                    @foreach ($errors->eventWijzigen->all() as $melding)
                        <p>{{ $melding }}</p>
                    @endforeach
                </div>
            @endif

            <form id="wijzigEventForm" method="POST" @if ($errors->eventWijzigen->any() && old('_edit_event'))
            action="{{ route('events.update', ['event' => old('_edit_event')]) }}" @endif>
                @csrf
                @method('PUT')

                <input type="hidden" id="wijzigEventId" name="_edit_event" value="{{ old('_edit_event') }}">

                <div class="form-groep">
                    <label for="wijzigEventNaam">Naam</label>
                    <input type="text" id="wijzigEventNaam" name="Naam" value="{{ old('Naam') }}" maxlength="100"
                        required>
                </div>

                <div class="form-groep">
                    <label for="wijzigEventDatum">Datum</label>
                    <input type="date" id="wijzigEventDatum" name="Datum" value="{{ old('Datum') }}" required>
                </div>

                <div class="form-groep">
                    <label for="wijzigEventTijd">Tijd</label>
                    <input type="time" id="wijzigEventTijd" name="Tijd" value="{{ old('Tijd') }}" required>
                </div>

                <div class="form-groep">
                    <label for="wijzigEventLocatie">Locatie</label>
                    <input type="text" id="wijzigEventLocatie" name="Locatie" value="{{ old('Locatie') }}"
                        maxlength="150" required>
                </div>

                <div class="modal-knoppen">
                    <button type="button" id="annuleerEventWijzigen" class="annuleer-knop"
                        onclick="closeWijzigEventModal()">
                        ANNULEREN
                    </button>

                    <button type="submit" class="opslaan-knop">
                        OPSLAAN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bevestiging voor het verwijderen van een event. -->
    <div class="modal" id="verwijderEventModal" role="dialog" aria-modal="true" aria-labelledby="verwijderEventTitel"
        aria-describedby="verwijderEventVraag">
        <div class="modal-inhoud">
            <div class="modal-kop">
                <div>
                    <p>Evenementen beheren</p>
                    <h2 id="verwijderEventTitel">Event verwijderen</h2>
                </div>

                <button type="button" class="modal-sluiten" onclick="closeVerwijderEventModal()" aria-label="Sluiten">
                    &times;
                </button>
            </div>

            <p id="verwijderEventVraag">
                Weet je zeker dat je dit event wilt verwijderen?
            </p>

            <form id="verwijderEventForm" method="POST">
                @csrf
                @method('DELETE')

                <input type="hidden" name="bevestiging" value="1">

                <div class="modal-knoppen">
                    <button type="button" id="annuleerEventVerwijderen" class="annuleer-knop"
                        onclick="closeVerwijderEventModal()">
                        ANNULEREN
                    </button>

                    <button type="submit" class="verwijderen-knop">
                        BEVESTIGEN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleMenu(knop) {
            const menu = document.getElementById('dashboardNav');
            const geopend = menu.classList.toggle('menu-open');
            knop.setAttribute('aria-expanded', geopend ? 'true' : 'false');
        }

        function openEventModal() {
            document.getElementById('eventModal').classList.add('actief');
        }

        function closeEventModal() {
            document.getElementById('eventModal').classList.remove('actief');
        }

        document.getElementById('eventModal').addEventListener('click', function (event) {
            if (event.target === this) {
                closeEventModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeEventModal();
            }
        });
        let laatsteEventWijzigKnop = null;

        // Vul het formulier met de gegevens van het aangeklikte evenement.
        function openWijzigEventModal(knop) {
            const event = JSON.parse(knop.dataset.event);

            laatsteEventWijzigKnop = knop;

            document.getElementById('wijzigEventForm').action =
                knop.dataset.updateUrl;

            document.getElementById('wijzigEventId').value = event.Id;
            document.getElementById('wijzigEventNaam').value = event.Naam;
            document.getElementById('wijzigEventDatum').value = event.Datum;
            document.getElementById('wijzigEventTijd').value = event.Tijd;
            document.getElementById('wijzigEventLocatie').value = event.Locatie;

            // Oude meldingen horen niet bij een nieuwe wijzigpoging.
            document.querySelectorAll('#wijzigEventModal .melding-fout')
                .forEach((melding) => melding.remove());

            document.getElementById('wijzigEventModal').classList.add('actief');
            document.getElementById('wijzigEventNaam').focus();
        }

        // Sluit de pop-up zonder gegevens op te slaan.
        function closeWijzigEventModal() {
            document.getElementById('wijzigEventModal')
                .classList.remove('actief');

            if (laatsteEventWijzigKnop) {
                laatsteEventWijzigKnop.focus();
            }
        }

        // Klikken op de achtergrond annuleert het wijzigen.
        document.getElementById('wijzigEventModal')
            .addEventListener('click', function (event) {
                if (event.target === this) {
                    closeWijzigEventModal();
                }
            });

        // Escape sluit de wijzigpop-up.
        document.addEventListener('keydown', function (event) {
            if (
                event.key === 'Escape'
                && document.getElementById('wijzigEventModal')
                    .classList.contains('actief')
            ) {
                closeWijzigEventModal();
            }
        });

        // Verberg meldingen op deze pagina na drie seconden.
        document.querySelectorAll('.melding-succes, .melding-fout')
            .forEach((melding) => {
                setTimeout(() => {
                    melding.hidden = true;
                }, 3000);
            });
        let laatsteEventVerwijderKnop = null;
        let eventVerwijderTimer;

        // Toon een verwijderfout gedurende drie seconden.
        function toonEventVerwijderFout(tekst) {
            const foutvak = document.getElementById('eventVerwijderenFout');

            clearTimeout(eventVerwijderTimer);

            foutvak.textContent = tekst;
            foutvak.hidden = false;
            foutvak.focus();

            eventVerwijderTimer = setTimeout(() => {
                foutvak.hidden = true;
            }, 3000);
        }

        // Controleer de getoonde datum en open de bevestiging.
        function openVerwijderEventModal(knop) {
            laatsteEventVerwijderKnop = knop;

            if (knop.dataset.vandaag === '1') {
                toonEventVerwijderFout(
                    'Dit event vindt vandaag plaats en kan niet worden verwijderd.'
                );
                return;
            }

            document.getElementById('eventVerwijderenFout').hidden = true;

            document.getElementById('verwijderEventForm').action =
                knop.dataset.verwijderUrl;

            document.getElementById('verwijderEventModal')
                .classList.add('actief');

            document.getElementById('annuleerEventVerwijderen').focus();
        }

        // Annuleren verstuurt geen verwijderverzoek.
        function closeVerwijderEventModal() {
            document.getElementById('verwijderEventModal')
                .classList.remove('actief');

            if (laatsteEventVerwijderKnop) {
                laatsteEventVerwijderKnop.focus();
            }
        }

        // Klikken op de achtergrond sluit de bevestiging.
        document.getElementById('verwijderEventModal')
            .addEventListener('click', function (event) {
                if (event.target === this) {
                    closeVerwijderEventModal();
                }
            });

        // Escape sluit de bevestiging.
        document.addEventListener('keydown', function (event) {
            if (
                event.key === 'Escape'
                && document.getElementById('verwijderEventModal')
                    .classList.contains('actief')
            ) {
                closeVerwijderEventModal();
            }
        });
    </script>

</body>

</html>