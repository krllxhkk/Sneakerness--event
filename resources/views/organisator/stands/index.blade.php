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

        <button type="button" class="burger-menu" aria-label="Menu openen" aria-expanded="false"
            aria-controls="dashboardNav" onclick="toggleMenu(this)">
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

                <button type="button" class="toevoegen-knop" onclick="openStandModal()">
                    STAND TOEVOEGEN
                </button>
            </div>

            <!-- Succes- en foutmeldingen -->
            @if (session('success'))
                <div class="melding-succes" role="status">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Fouten bij verwijderen verschijnen op het overzicht. -->
            <div id="standVerwijderenFout" class="melding-fout" role="alert" tabindex="-1" @if (!$errors->standVerwijderen->any()) hidden @endif>
                @foreach ($errors->standVerwijderen->all() as $melding)
                    <p>{{ $melding }}</p>
                @endforeach
            </div>

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
                            <th>Acties</th>
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
                                                    <td>{{ $stand->VerkoperNaam ?? '-' }}</td>
                                                    <td>
                                                        @if ($stand->VerhuurdStatus)
                                                            <span class="status-verhuurd">Verhuurd</span>
                                                        @else
                                                            <span class="status-beschikbaar">Beschikbaar</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <button type="button" class="opslaan-knop" data-stand="{{ json_encode([
                                'Id' => $stand->Id,
                                'Standnaam' => $stand->Standnaam,
                                'Locatie' => $stand->Locatie,
                                'Prijs' => $stand->Prijs,
                                'Kwaliteitsklasse' => $stand->Kwaliteitsklasse,
                                'VerhuurdStatus' => (int) $stand->VerhuurdStatus,
                                'VerkoperId' => $stand->VerkoperId,
                            ]) }}" data-update-url="{{ route('stands.update', ['stand' => $stand->Id]) }}"
                                                            onclick="openWijzigStandModal(this)">
                                                            WIJZIGEN
                                                        </button>
                                                        <button type="button" class="verwijderen-knop"
                                                            data-verwijder-url="{{ route('stands.destroy', ['stand' => $stand->Id]) }}"
                                                            data-gereserveerd="{{ ($stand->VerhuurdStatus || $stand->VerkoperId !== null) ? '1' : '0' }}"
                                                            onclick="openVerwijderStandModal(this)">
                                                            VERWIJDEREN
                                                        </button>
                                                    </td>
                                                </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="lege-tabel">
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
    <div class="modal {{ $errors->any() ? 'actief' : '' }}" id="standModal" role="dialog" aria-modal="true"
        aria-labelledby="standModalTitel">

        <div class="modal-inhoud">
            <div class="modal-kop">
                <div>
                    <p>Stands beheren</p>
                    <h2 id="standModalTitel">Stand toevoegen</h2>
                </div>

                <button type="button" class="modal-sluiten" onclick="closeStandModal()" aria-label="Sluiten">
                    &times;
                </button>
            </div>

            <form action="{{ route('stands.store') }}" method="POST">
                @csrf

                <!-- Standnummer wordt automatisch aangemaakt -->

                <div class="form-groep">
                    <label for="standnaam">Standnaam</label>
                    <input type="text" id="standnaam" name="Standnaam" value="{{ old('Standnaam') }}" maxlength="100"
                        required>
                    @error('Standnaam')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="standlocatie">Locatie</label>
                    <input type="text" id="standlocatie" name="Locatie" value="{{ old('Locatie') }}" maxlength="100"
                        required>
                    @error('Locatie')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="standprijs">Prijs (€)</label>
                    <input type="number" id="standprijs" name="Prijs" min="0" step="0.01" value="{{ old('Prijs') }}"
                        required>
                    @error('Prijs')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-groep">
                    <label for="kwaliteitsklasse">Kwaliteitsklasse</label>
                    <input type="text" id="kwaliteitsklasse" name="Kwaliteitsklasse"
                        value="{{ old('Kwaliteitsklasse') }}" maxlength="30">
                    @error('Kwaliteitsklasse')
                        <span class="formulier-fout">{{ $message }}</span>
                    @enderror
                </div>

                <div class="modal-knoppen">
                    <button type="button" class="annuleer-knop" onclick="closeStandModal()">
                        ANNULEREN
                    </button>

                    <button type="submit" class="opslaan-knop">
                        OPSLAAN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pop-up voor een bestaande stand wijzigen -->
    <div class="modal {{ $errors->standWijzigen->any() ? 'actief' : '' }}" id="wijzigStandModal" role="dialog"
        aria-modal="true" aria-labelledby="wijzigStandTitel">
        <div class="modal-inhoud">
            <div class="modal-kop">
                <div>
                    <p>Stands beheren</p>
                    <h2 id="wijzigStandTitel">Stand wijzigen</h2>
                </div>

                <button type="button" class="modal-sluiten" onclick="closeWijzigStandModal()" aria-label="Sluiten">
                    &times;
                </button>
            </div>

            @if ($errors->standWijzigen->any())
                <div class="melding-fout" role="alert">
                    @foreach ($errors->standWijzigen->all() as $melding)
                        <p>{{ $melding }}</p>
                    @endforeach
                </div>
            @endif

            <form id="wijzigStandForm" method="POST" @if ($errors->standWijzigen->any() && old('_edit_stand'))
            action="{{ route('stands.update', ['stand' => old('_edit_stand')]) }}" @endif>
                @csrf
                @method('PUT')

                <input type="hidden" id="wijzigStandId" name="_edit_stand" value="{{ old('_edit_stand') }}">

                <div class="form-groep">
                    <label for="wijzigStandnaam">Standnaam</label>
                    <input type="text" id="wijzigStandnaam" name="Standnaam" value="{{ old('Standnaam') }}"
                        maxlength="100" required>
                </div>

                <div class="form-groep">
                    <label for="wijzigLocatie">Locatie</label>
                    <input type="text" id="wijzigLocatie" name="Locatie" value="{{ old('Locatie') }}" maxlength="100"
                        required>
                </div>

                <div class="form-groep">
                    <label for="wijzigPrijs">Prijs (€)</label>
                    <input type="number" id="wijzigPrijs" name="Prijs" value="{{ old('Prijs') }}" min="0"
                        max="999999.99" step="0.01" required>
                </div>

                <div class="form-groep">
                    <label for="wijzigKwaliteitsklasse">Kwaliteitsklasse</label>
                    <input type="text" id="wijzigKwaliteitsklasse" name="Kwaliteitsklasse"
                        value="{{ old('Kwaliteitsklasse') }}" maxlength="30">
                </div>

                <div class="form-groep">
                    <label for="wijzigStatus">Status</label>
                    <select id="wijzigStatus" name="VerhuurdStatus" required>
                        <option value="0" @selected(old('VerhuurdStatus', '0') == '0')>
                            Beschikbaar
                        </option>
                        <option value="1" @selected(old('VerhuurdStatus') == '1')>
                            Verhuurd
                        </option>
                    </select>
                </div>

                <div class="form-groep">
                    <label for="wijzigVerkoper">Verkoper</label>
                    <select id="wijzigVerkoper" name="VerkoperId">
                        <option value="">Geen verkoper</option>

                        @foreach ($verkopers as $verkoper)
                            <option value="{{ $verkoper->Id }}" @selected(old('VerkoperId') == $verkoper->Id)>
                                {{ $verkoper->Naam }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="modal-knoppen">
                    <button type="button" class="annuleer-knop" onclick="closeWijzigStandModal()">
                        ANNULEREN
                    </button>

                    <button type="submit" class="opslaan-knop">
                        OPSLAAN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bevestigingspop-up voor het verwijderen van een stand. -->
    <div class="modal" id="verwijderStandModal" role="dialog" aria-modal="true" aria-labelledby="verwijderStandTitel"
        aria-describedby="verwijderStandVraag">
        <div class="modal-inhoud">
            <div class="modal-kop">
                <div>
                    <p>Stands beheren</p>
                    <h2 id="verwijderStandTitel">Stand verwijderen</h2>
                </div>

                <button type="button" class="modal-sluiten" onclick="closeVerwijderStandModal()" aria-label="Sluiten">
                    &times;
                </button>
            </div>

            <p id="verwijderStandVraag">
                Weet je zeker dat je deze stand wilt verwijderen?
            </p>

            <form id="verwijderStandForm" method="POST">
                @csrf
                @method('DELETE')

                <input type="hidden" name="bevestiging" value="1">

                <div class="modal-knoppen">
                    <button type="button" id="annuleerVerwijderen" class="annuleer-knop"
                        onclick="closeVerwijderStandModal()">
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

        // Vul de pop-up met de gegevens van de aangeklikte stand.
        function openWijzigStandModal(knop) {
            const stand = JSON.parse(knop.dataset.stand);

            document.getElementById('wijzigStandForm').action =
                knop.dataset.updateUrl;

            document.getElementById('wijzigStandId').value = stand.Id;
            document.getElementById('wijzigStandnaam').value = stand.Standnaam ?? '';
            document.getElementById('wijzigLocatie').value = stand.Locatie ?? '';
            document.getElementById('wijzigPrijs').value = stand.Prijs ?? '';
            document.getElementById('wijzigKwaliteitsklasse').value =
                stand.Kwaliteitsklasse ?? '';
            document.getElementById('wijzigStatus').value =
                String(stand.VerhuurdStatus);
            document.getElementById('wijzigVerkoper').value =
                stand.VerkoperId ?? '';

            // Verwijder meldingen van een eerdere mislukte poging.
            document.querySelectorAll('#wijzigStandModal .melding-fout')
                .forEach((melding) => melding.remove());

            document.getElementById('wijzigStandModal').classList.add('actief');
            document.getElementById('wijzigStandnaam').focus();
        }

        // Sluit de pop-up zonder op te slaan.
        function closeWijzigStandModal() {
            document.getElementById('wijzigStandModal').classList.remove('actief');
        }

        // Klikken op de achtergrond sluit de pop-up.
        document.getElementById('wijzigStandModal')
            .addEventListener('click', function (event) {
                if (event.target === this) {
                    closeWijzigStandModal();
                }
            });

        // Escape sluit de pop-up.
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeWijzigStandModal();
            }
        });

        let laatsteVerwijderKnop = null;

        // Controleer de getoonde status en open zo nodig de bevestigingspop-up.
        function openVerwijderStandModal(knop) {
            const foutvak = document.getElementById('standVerwijderenFout');

            laatsteVerwijderKnop = knop;

            // Toon direct de voorgeschreven melding bij een gereserveerde stand.
            if (knop.dataset.gereserveerd === '1') {
                foutvak.textContent =
                    'Deze stand is al gereserveerd en kan niet worden verwijderd';

                foutvak.hidden = false;
                verbergMeldingNaDrieSeconden(foutvak);
                foutvak.focus();
                return;
            }

            foutvak.hidden = true;

            document.getElementById('verwijderStandForm').action =
                knop.dataset.verwijderUrl;

            document.getElementById('verwijderStandModal')
                .classList.add('actief');

            document.getElementById('annuleerVerwijderen').focus();
        }

        // Annuleren sluit alleen de pop-up: er wordt geen verzoek verstuurd.
        function closeVerwijderStandModal() {
            document.getElementById('verwijderStandModal')
                .classList.remove('actief');

            if (laatsteVerwijderKnop) {
                laatsteVerwijderKnop.focus();
            }
        }

        // Klikken naast de pop-up annuleert het verwijderen.
        document.getElementById('verwijderStandModal')
            .addEventListener('click', function (event) {
                if (event.target === this) {
                    closeVerwijderStandModal();
                }
            });

        // Escape annuleert het verwijderen.
        document.addEventListener('keydown', function (event) {
            if (
                event.key === 'Escape'
                && document.getElementById('verwijderStandModal')
                    .classList.contains('actief')
            ) {
                closeVerwijderStandModal();
            }
        });

        // Verberg succes- en foutmeldingen na 3 seconden.
        function verbergMeldingNaDrieSeconden(melding) {
            setTimeout(() => {
                melding.hidden = true;
            }, 3000);
        }

        // Meldingen die bij het laden van de pagina zichtbaar zijn.
        document.querySelectorAll('.melding-succes, .melding-fout')
            .forEach((melding) => {
                if (!melding.hidden) {
                    verbergMeldingNaDrieSeconden(melding);
                }
    });
    </script>

</body>

</html>