<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verkopers | Sneakerness®</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
<!-- Navigatiebalk met het hamburgermenu -->
    <header class="dashboard-header">

        
<div class="dashboard-logo">
    SNEAKERNESS<span>®</span>
</div>

<button class="burger-menu"
        id="burgerMenu"
        type="button"
        aria-label="Menu openen"
        aria-expanded="false">
    <span></span>
    <span></span>
    <span></span>
</button>

<nav class="dashboard-nav">
    <a href="{{ route('organisator.dashboard') }}">
        Dashboard
    </a>

                <a href="{{ route('organisator.events') }}">
                    Evenementen
                </a>

    <a href="{{ route('organisator.tickets.index') }}">
        Tickets
    </a>

    <a href="{{ route('organisator.verkopers') }}">
        Verkopers
    </a>

    <a href="{{ route('organisator.stands') }}">
        Stands
    </a>
</nav>


        <form method="POST" action="{{ route('logout') }}" class="uitloggen-formulier">
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

                <a href="{{ route('organisator.dashboard') }}">
                    Dashboard
                </a>

                <a href="{{ route('organisator.events') }}">
                    Evenementen
                </a>

                <a href="{{ route('organisator.tickets.index') }}">
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
         <!-- Modal verkoper toevoegen -->
<div id="verkoperModal" class="modal">

    <div class="modal-inhoud">

        <div class="modal-kop">
            <div>
                <p>Organisator Dashboard</p>
                <h2>Verkoper toevoegen</h2>
            </div>

            <button type="button" class="modal-sluiten" id="closeVerkoperModal">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('verkopers.store') }}">

            @csrf

            <div class="form-groep">
                <label for="Naam">Naam *</label>

                <input
                    type="text"
                    id="Naam"
                    name="Naam"
                    value="{{ old('Naam') }}"
                    maxlength="100"
                    required
                >
            </div>

            <div class="form-groep">
                <label for="VerkooptSoort">Verkoopt soort *</label>

                <input
                    type="text"
                    id="VerkooptSoort"
                    name="VerkooptSoort"
                    value="{{ old('VerkooptSoort') }}"
                    maxlength="100"
                    placeholder="Bijvoorbeeld: Sneakers"
                    required
                >
            </div>

            <div class="form-groep">
                <label for="StandType">Stand type *</label>

                <select id="StandType" name="StandType" required>
                    <option value="">Kies een stand type</option>

                    <option value="A" {{ old('StandType') == 'A' ? 'selected' : '' }}>
                        A
                    </option>

                    <option value="AA" {{ old('StandType') == 'AA' ? 'selected' : '' }}>
                        AA
                    </option>

                    <option value="AA+" {{ old('StandType') == 'AA+' ? 'selected' : '' }}>
                        AA+
                    </option>
                </select>
            </div>

            <div class="form-groep">
    <label for="Dagen">Dagen *</label>

    <select id="Dagen" name="Dagen" required>
        <option value="">Kies een dag</option>

        <option value="Zaterdag" {{ old('Dagen') == 'Zaterdag' ? 'selected' : '' }}>
            Zaterdag
        </option>

        <option value="Zondag" {{ old('Dagen') == 'Zondag' ? 'selected' : '' }}>
            Zondag
        </option>

        <option value="Zaterdag en zondag" {{ old('Dagen') == 'Zaterdag en zondag' ? 'selected' : '' }}>
            Zaterdag en zondag
        </option>
    </select>
</div>

            <div class="form-groep">
                <label for="SpecialeStatus">Speciale status</label>

                <select id="SpecialeStatus" name="SpecialeStatus">
                    <option value="">Geen</option>

                    <option
                        value="Partner"
                        {{ old('SpecialeStatus') == 'Partner' ? 'selected' : '' }}
                    >
                        Partner
                    </option>

                    <option
                        value="Premium"
                        {{ old('SpecialeStatus') == 'Premium' ? 'selected' : '' }}
                    >
                        Premium
                    </option>
                </select>
            </div>

            <div class="modal-knoppen">

                <button
                    type="button"
                    class="annuleer-knop"
                    id="cancelVerkoperModal"
                >
                    ANNULEREN
                </button>

                <button
                    type="submit"
                    class="opslaan-knop"
                >
                    VERKOPER TOEVOEGEN
                </button>

            </div>

        </form>

    </div>

</div>

        <!-- Verkopers inhoud -->
        <main class="dashboard-inhoud">

<!-- Modal contactpersoon toevoegen -->
<div id="contactpersoonModal" class="modal">
    <div class="modal-inhoud">
        <div class="modal-kop">
            <div>
                <p>Organisator Dashboard</p>
                <h2>Contactpersoon toevoegen</h2>
            </div>

            <button type="button"
                    class="modal-sluiten"
                    id="closeContactpersoonModal">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('contactpersonen.store') }}">
            @csrf

            <div class="form-groep">
                <label for="ContactpersoonNaam">Naam *</label>
                <input type="text"
                       id="ContactpersoonNaam"
                       name="Naam"
                       value="{{ old('Naam') }}"
                       maxlength="100"
                       required>
            </div>

            <div class="form-groep">
                <label for="ContactpersoonEmail">E-mailadres *</label>
                <input type="email"
                       id="ContactpersoonEmail"
                       name="E-mailadres"
                       value="{{ old('E-mailadres') }}"
                       maxlength="150"
                       required>
            </div>

            <div class="form-groep">
                <label for="ContactpersoonTelefoon">Telefoonnummer *</label>
                <input type="text"
                       id="ContactpersoonTelefoon"
                       name="Telefoonnummer"
                       value="{{ old('Telefoonnummer') }}"
                       maxlength="20"
                       required>
            </div>

            <div class="form-groep">
                <label for="ContactpersoonVerkoper">Verkoper *</label>
                <select id="ContactpersoonVerkoper"
                        name="VerkoperId"
                        required>
                    <option value="">Kies een verkoper</option>

                    @foreach ($verkopers as $verkoper)
                        <option value="{{ $verkoper->Id }}"
                            {{ old('VerkoperId') == $verkoper->Id ? 'selected' : '' }}>
                            {{ $verkoper->Naam }}
                        </option>
                    @endforeach
                </select>
            </div>

          
<div class="form-groep">
    <label for="ContactpersoonStatus">Status *</label>
    <select id="ContactpersoonStatus" name="Isactief" required>
        <option value="1" {{ old('Isactief', '1') == '1' ? 'selected' : '' }}>
            Actief
        </option>
        <option value="0" {{ old('Isactief') == '0' ? 'selected' : '' }}>
            Inactief
        </option>
    </select>
</div>


            <div class="modal-knoppen">
                <button type="button"
                        class="annuleer-knop"
                        id="cancelContactpersoonModal">
                    ANNULEREN
                </button>

                <button type="submit" class="opslaan-knop">
                    CONTACTPERSOON TOEVOEGEN
                </button>
            </div>
        </form>
    </div>
</div>

            <div class="dashboard-kop">

                <p>Organisator Dashboard</p>

                <h1>Verkopers</h1>

                <span>
                    Beheer de verkopers en contactpersonen van Sneakerness®.
                </span>

            </div>


            <!-- Verkopers -->
            <section class="organisator-verkopers-overzicht">
        
<!-- Melding na het toevoegen van een verkoper -->
@if (session('success'))
    <div class="melding-succes">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="melding-fout">
        {{ session('error') }}
    </div>
@endif


    <div class="verkopers-sectie-kop">

        <h2>VERKOPERS</h2>

       <button type="button" class="toevoegen-knop" id="openVerkoperModal">
    + TOEVOEGEN
</button>

    </div>

    @if ($verkopersFout)

                    <div class="database-error">
                        <div class="database-error-icon">
                            !
                        </div>

                        <div class="database-error-content">
                            <span>DATABASE ERROR</span>

                            <h2>VERKOPERS KUNNEN NIET WORDEN OPGEHAALD</h2>

                            <p>{{ $verkopersFout }}</p>

                            <small>
                                Probeer het later opnieuw.
                            </small>
                        </div>
                    </div>

                @else

                    <div class="dashboard-tabel verkopers-tabel">

                        <table>

                            <thead>
                                <tr>
                                    <th>NAAM</th>
                                    <th>VERKOOPT</th>
                                    <th>STAND TYPE</th>
                                    <th>DAGEN</th>
                                    <th>STATUS</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($verkopers as $verkoper)

                                    <tr>

                                        <td>
                                            <strong>{{ $verkoper->Naam }}</strong>
                                        </td>

                                        <td>
                                            {{ $verkoper->VerkooptSoort }}
                                        </td>

                                        <td>
                                            {{ $verkoper->StandType }}
                                        </td>

                                        <td>
                                            {{ $verkoper->Dagen }}
                                        </td>

                                        <td>
                                            {{ $verkoper->SpecialeStatus ?? 'Geen' }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="5" class="lege-tabel">
                                            Er zijn momenteel geen actieve verkopers.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                @endif

            </section>


            <!-- Contactpersonen -->
            <section class="contactpersonen-section">

                

<div class="sectie-kop">
    <div>
        <p>STANDHOUDERS</p>
        <h2>CONTACTPERSONEN</h2>
    </div>

    <div class="contactpersoon-acties">
        <button type="button"
                class="toevoegen-knop"
                id="openContactpersoonModal">
            + TOEVOEGEN
        </button>

        <button type="button"
                class="toevoegen-knop"
                id="openKoppelenModal">
            KOPPELEN AAN VERKOPER
        </button>
    </div>

<!-- Modal contactpersoon koppelen -->
<div id="koppelenModal" class="modal">
    <div class="modal-inhoud">
        <div class="modal-kop">
            <div>
                <p>Organisator Dashboard</p>
                <h2>Contactpersoon koppelen</h2>
            </div>

            <button type="button"
                    class="modal-sluiten"
                    id="closeKoppelenModal">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('contactpersonen.koppel') }}">
            @csrf

            <div class="form-groep">
                <label for="KoppelContactpersoonId">Contactpersoon *</label>
                <select id="KoppelContactpersoonId"
                        name="ContactpersoonId"
                        required>
                    <option value="">Kies een contactpersoon</option>

                    @foreach ($contactpersonen as $contactpersoon)
                        <option value="{{ $contactpersoon->Id }}">
                            {{ $contactpersoon->Naam }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-groep">
                <label for="KoppelVerkoperId">Verkoper *</label>
                <select id="KoppelVerkoperId"
                        name="VerkoperId"
                        required>
                    <option value="">Kies een verkoper</option>

                    @foreach ($verkopers as $verkoper)
                        <option value="{{ $verkoper->Id }}">
                            {{ $verkoper->Naam }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="modal-knoppen">
                <button type="button"
                        class="annuleer-knop"
                        id="cancelKoppelenModal">
                    ANNULEREN
                </button>

                <button type="submit" class="opslaan-knop">
                    CONTACTPERSOON KOPPELEN
                </button>
            </div>
        </form>
    </div>
</div>

</div>




                @if ($contactpersonenFout)

                    <div class="database-error">

                        <div class="database-error-icon">
                            !
                        </div>

                        <div class="database-error-content">

                            <span>DATABASE ERROR</span>

                            <h2>
                                CONTACTPERSONEN KUNNEN NIET WORDEN OPGEHAALD
                            </h2>

                            <p>
                                {{ $contactpersonenFout }}
                            </p>

                            <small>
                                Probeer het later opnieuw.
                            </small>

                        </div>

                    </div>

                @else

                    <div class="dashboard-tabel">

                        <table>

                            <thead>
                                <tr>
                                    <th>NAAM</th>
                                    <th>VERKOPER</th>
                                    <th>E-MAILADRES</th>
                                    <th>TELEFOON</th>
                                    <th>STATUS</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($contactpersonen as $contactpersoon)

                                    <tr>

                                        <td>
                                            <strong>
                                                {{ $contactpersoon->Naam }}
                                            </strong>
                                        </td>

                                        <td>
                                            {{ $contactpersoon->VerkoperNaam ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $contactpersoon->{'E-mailadres'} }}
                                        </td>

                                        <td>
                                            {{ $contactpersoon->Telefoonnummer }}
                                        </td>

                                        <td>
                                            <span class="status-actief">
                                                ● Actief
                                            </span>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="5" class="lege-tabel">
                                            Er zijn momenteel geen contactpersonen.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                @endif

            </section>

        </main>

    </div>
<script>
    const modal = document.getElementById('verkoperModal');
    const openButton = document.getElementById('openVerkoperModal');
    const closeButton = document.getElementById('closeVerkoperModal');
    const cancelButton = document.getElementById('cancelVerkoperModal');

    openButton.addEventListener('click', function () {
        modal.classList.add('actief');
    });

    closeButton.addEventListener('click', function () {
        modal.classList.remove('actief');
    });

    cancelButton.addEventListener('click', function () {
        modal.classList.remove('actief');
    });

    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.classList.remove('actief');
        }
    });
// Verberg meldingen na 4 seconden
setTimeout(function () {
    const meldingen = document.querySelectorAll(
        '.melding-succes, .melding-fout'
    );

    meldingen.forEach(function (melding) {
        melding.style.transition = 'opacity 0.5s ease';
        melding.style.opacity = '0';

        setTimeout(function () {
            melding.remove();
        }, 500);
    });
}, 4000);

const contactpersoonModal = document.getElementById('contactpersoonModal');
const openContactpersoonButton = document.getElementById('openContactpersoonModal');
const closeContactpersoonButton = document.getElementById('closeContactpersoonModal');
const cancelContactpersoonButton = document.getElementById('cancelContactpersoonModal');

openContactpersoonButton.addEventListener('click', function () {
    contactpersoonModal.classList.add('actief');
});

closeContactpersoonButton.addEventListener('click', function () {
    contactpersoonModal.classList.remove('actief');
});

cancelContactpersoonButton.addEventListener('click', function () {
    contactpersoonModal.classList.remove('actief');
});

contactpersoonModal.addEventListener('click', function (event) {
    if (event.target === contactpersoonModal) {
        contactpersoonModal.classList.remove('actief');
    }
});

const koppelenModal = document.getElementById('koppelenModal');
const openKoppelenButton = document.getElementById('openKoppelenModal');
const closeKoppelenButton = document.getElementById('closeKoppelenModal');
const cancelKoppelenButton = document.getElementById('cancelKoppelenModal');

openKoppelenButton.addEventListener('click', function () {
    koppelenModal.classList.add('actief');
});

closeKoppelenButton.addEventListener('click', function () {
    koppelenModal.classList.remove('actief');
});

cancelKoppelenButton.addEventListener('click', function () {
    koppelenModal.classList.remove('actief');
});

koppelenModal.addEventListener('click', function (event) {
    if (event.target === koppelenModal) {
        koppelenModal.classList.remove('actief');
    }
});

const burgerMenu = document.getElementById('burgerMenu');
const dashboardNav = document.querySelector('.dashboard-header .dashboard-nav');

burgerMenu.addEventListener('click', function () {
    const isOpen = dashboardNav.classList.toggle('menu-open');

    burgerMenu.setAttribute('aria-expanded', String(isOpen));
    burgerMenu.setAttribute(
        'aria-label',
        isOpen ? 'Menu sluiten' : 'Menu openen'
    );
});


</script>
</body>

</html>