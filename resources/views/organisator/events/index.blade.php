<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evenementen beheren | Sneakerness®</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <style>
        .beheer-kop {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .beheer-kop h1 {
            font-size: 36px;
            margin-bottom: 8px;
        }

        .beheer-kop p {
            color: #888;
            font-size: 14px;
        }

        .beheer-tabel {
            overflow-x: auto;
            border: 1px solid #252525;
            border-radius: 6px;
        }

        .beheer-tabel table {
            width: 100%;
            border-collapse: collapse;
            background: #0d0d0d;
            min-width: 780px;
        }

        .beheer-tabel th {
            padding: 16px;
            color: #888;
            border-bottom: 1px solid #333;
            font-size: 11px;
            text-align: left;
            text-transform: uppercase;
        }

        .beheer-tabel td {
            padding: 16px;
            color: #ddd;
            border-bottom: 1px solid #252525;
            font-size: 13px;
        }

        .beheer-tabel tbody tr:hover {
            background: #151515;
        }

        .beheer-naam {
            color: #eaff00;
            font-weight: bold;
        }

        .formulier-fout {
            color: #ff7777;
            font-size: 12px;
        }

        .form-groep input:focus {
            outline: 1px solid #eaff00;
        }

        @media (max-width: 768px) {
            .beheer-kop h1 {
                font-size: 28px;
            }

            .beheer-kop .toevoegen-knop {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- Bovenste navigatie -->
    <header class="dashboard-header">
        <div class="dashboard-logo">
            SNEAKERNESS<span>®</span>
        </div>

        <button type="button" class="burger-menu"
                aria-label="Menu openen"
                aria-expanded="false"
                aria-controls="dashboardNav"
                onclick="toggleMenu(this)">
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

        <form method="POST" action="{{ route('logout') }}"
              class="uitloggen-formulier">
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

                <button type="button" class="toevoegen-knop"
                        onclick="openEventModal()">
                    EVENT TOEVOEGEN
                </button>
            </div>

            <!-- Succes- en foutmeldingen -->
            @if (session('success'))
                <div class="melding-succes" role="status">
                    {{ session('success') }}
                </div>
            @endif

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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="lege-tabel">
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
    <div class="modal {{ $errors->any() ? 'actief' : '' }}"
         id="eventModal"
         role="dialog"
         aria-modal="true"
         aria-labelledby="eventModalTitel">

        <div class="modal-inhoud">
            <div class="modal-kop">
                <div>
                    <p>Evenementen beheren</p>
                    <h2 id="eventModalTitel">Event toevoegen</h2>
                </div>

                <button type="button" class="modal-sluiten"
                        onclick="closeEventModal()" aria-label="Sluiten">
                    &times;
                </button>
            </div>

            <form action="{{ route('events.store') }}" method="POST">
                @csrf

                <div class="form-groep">
                    <label for="eventNaam">Naam</label>
                    <input type="text" id="eventNaam" name="Naam"
                           value="{{ old('Naam') }}" maxlength="100" required>
                    @error('Naam')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="eventDatum">Datum</label>
                    <input type="date" id="eventDatum" name="Datum"
                           min="{{ date('Y-m-d') }}"
                           value="{{ old('Datum') }}" required>
                    @error('Datum')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="eventTijd">Tijd</label>
                    <input type="time" id="eventTijd" name="Tijd"
                           value="{{ old('Tijd') }}" required>
                    @error('Tijd')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="eventLocatie">Locatie</label>
                    <input type="text" id="eventLocatie" name="Locatie"
                           value="{{ old('Locatie') }}" maxlength="150" required>
                    @error('Locatie')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="eventTickets">Tickets per tijdslot</label>
                    <input type="number" id="eventTickets"
                           name="AantalTicketsPerTijdslot"
                           min="0"
                           value="{{ old('AantalTicketsPerTijdslot', 0) }}"
                           required>
                    @error('AantalTicketsPerTijdslot')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="eventStands">Beschikbare stands</label>
                    <input type="number" id="eventStands"
                           name="BeschikbareStands"
                           min="0"
                           value="{{ old('BeschikbareStands', 0) }}"
                           required>
                    @error('BeschikbareStands')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="modal-knoppen">
                    <button type="button" class="annuleer-knop"
                            onclick="closeEventModal()">
                        ANNULEREN
                    </button>

                    <button type="submit" class="opslaan-knop">
                        OPSLAAN
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
    </script>

</body>
</html>