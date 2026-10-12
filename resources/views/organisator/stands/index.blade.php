
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stands beheren | Sneakerness®</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
            <a href="{{ route('organisator.events') }}">Evenementen</a>
            <a href="{{ route('organisator.tickets.index') }}">Tickets</a>
            <a href="{{ route('organisator.verkopers') }}">Verkopers</a>
            <a href="{{ route('organisator.stands') }}" class="actief">Stands</a>
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
                <a href="{{ route('organisator.events') }}">Evenementen</a>
                <a href="{{ route('organisator.tickets.index') }}">Tickets</a>
                <a href="{{ route('organisator.verkopers') }}">Verkopers</a>
                <a href="{{ route('organisator.stands') }}" class="actief">Stands</a>
            </nav>
        </aside>

        <main class="dashboard-inhoud">

            <!-- Titel en knop -->
            <div class="beheer-kop">
                <div>
                    <h1>STANDS</h1>
                    <p>{{ $stands->count() }} stands totaal</p>
                </div>

                <button type="button" class="toevoegen-knop"
                        onclick="openStandModal()">
                    STAND TOEVOEGEN
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
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Tabel met stands -->
            <div class="beheer-tabel">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Standnummer</th>
                            <th>Standnaam</th>
                            <th>Locatie</th>
                            <th>Type</th>
                            <th>Prijs</th>
                            <th>Verkoper</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($stands as $stand)
                            <tr>
                                <td>{{ $stand->Id }}</td>
                                <td>ST-{{ str_pad($stand->Standnummer ?? $stand->Id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="beheer-naam">{{ $stand->Standnaam ?? '-' }}</td>
                                <td>{{ $stand->Locatie ?? '-' }}</td>
                                <td>{{ $stand->StandType ?? '-' }}</td>
                                <td>€{{ number_format((float) $stand->Prijs, 2, ',', '.') }}</td>
                                <td>{{ $stand->verkoper?->Naam ?? '-' }}</td>
                                <td>
                                    @if ($stand->VerhuurdStatus)
                                        <span class="status-verhuurd">Verhuurd</span>
                                    @else
                                        <span class="status-beschikbaar">Beschikbaar</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="lege-tabel">
                                    Er zijn nog geen stands beschikbaar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </main>
    </div>

    <!-- Popup voor een stand toevoegen -->
    <div class="modal {{ $errors->any() ? 'actief' : '' }}"
         id="standModal"
         role="dialog"
         aria-modal="true"
         aria-labelledby="standModalTitel">

        <div class="modal-inhoud">
            <div class="modal-kop">
                <div>
                    <p>Stands beheren</p>
                    <h2 id="standModalTitel">Stand toevoegen</h2>
                </div>

                <button type="button" class="modal-sluiten"
                        onclick="closeStandModal()" aria-label="Sluiten">
                    &times;
                </button>
            </div>

            <form action="{{ route('stands.store') }}" method="POST">
                @csrf

                <!-- Standnummer wordt automatisch aangemaakt -->

                <div class="form-groep">
                    <label for="standnaam">Standnaam</label>
                    <input type="text" id="standnaam" name="Standnaam"
                           value="{{ old('Standnaam') }}"
                           maxlength="100" required>
                    @error('Standnaam')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="standlocatie">Locatie</label>
                    <input type="text" id="standlocatie" name="Locatie"
                           value="{{ old('Locatie') }}"
                           maxlength="100" required>
                    @error('Locatie')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="standprijs">Prijs (€)</label>
                    <input type="number" id="standprijs" name="Prijs"
                           min="0" step="0.01"
                           value="{{ old('Prijs') }}" required>
                    @error('Prijs')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="kwaliteitsklasse">Kwaliteitsklasse</label>
                    <input type="text" id="kwaliteitsklasse"
                           name="Kwaliteitsklasse"
                           value="{{ old('Kwaliteitsklasse') }}"
                           maxlength="30">
                    @error('Kwaliteitsklasse')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="modal-knoppen">
                    <button type="button" class="annuleer-knop"
                            onclick="closeStandModal()">
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

        function openStandModal() {
            document.getElementById('standModal').classList.add('actief');
        }

        function closeStandModal() {
            document.getElementById('standModal').classList.remove('actief');
        }

        document.getElementById('standModal').addEventListener('click', function (event) {
            if (event.target === this) {
                closeStandModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeStandModal();
            }
        });
    </script>

</body>
</html>
