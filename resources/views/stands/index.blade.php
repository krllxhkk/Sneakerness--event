<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Stands | Sneakerness®</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/stands.css') }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>

    @include('partials.navbar')

    <main class="stands-container">

        <!-- Header -->
        <section class="stands-header">
            <span class="stands-label">SNEAKERNESS® STANDS</span>

            <h1>STANDS<br>OVERZICHT</h1>

            <p>
                Bekijk alle stands van Sneakerness® en zie welke stands
                beschikbaar of verhuurd zijn.
            </p>
        </section>

        <!-- Succesmelding -->
        @if (session('success'))
            <p class="stand-success" role="status">
                {{ session('success') }}
            </p>
        @endif

        <!-- Foutmelding bij een mislukte reservering -->
        @if ($errors->any())
            <div class="reservation-alert" id="reservationAlert" role="alert">
                <div class="reservation-alert-icon">!</div>

                <div class="reservation-alert-content">
                    <strong>RESERVERING MISLUKT</strong>

                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>

                <button type="button" class="reservation-alert-close"
                    onclick="document.getElementById('reservationAlert').remove()" aria-label="Melding sluiten">
                    &times;
                </button>
            </div>
        @endif

        <!-- Overzicht van alle stands -->
        @if ($stands->count() > 0)

            <section class="stands-grid">

                @foreach ($stands as $stand)

                    <article class="stand-card">

                        <div class="stand-card-top">
                            <span class="stand-type">
                                STAND {{ $stand->StandType }}
                            </span>

                            @if ($stand->VerhuurdStatus)
                                <span class="status rented">
                                    GERESERVEERD
                                </span>
                            @else
                                <span class="status available">
                                    BESCHIKBAAR
                                </span>
                            @endif
                        </div>

                        <!-- Naam van de stand -->
                        <h2>
                            @if ($stand->Standnaam)
                                {{ $stand->Standnaam }}
                            @elseif ($stand->VerhuurdStatus && $stand->verkoper)
                                {{ $stand->verkoper->Naam }}
                            @else
                                VRIJE STAND
                            @endif
                        </h2>

                        <div class="stand-details">

                            <div class="stand-detail">
                                <span>STANDNUMMER</span>
                                <strong>
                                    {{ $stand->Standnummer ?? '-' }}
                                </strong>
                            </div>

                            <div class="stand-detail">
                                <span>LOCATIE</span>
                                <strong>
                                    {{ $stand->Locatie ?? '-' }}
                                </strong>
                            </div>

                            <div class="stand-detail">
                                <span>STATUS</span>
                                <strong>
                                    {{ $stand->VerhuurdStatus ? 'Gereserveerd' : 'Beschikbaar' }}
                                </strong>
                            </div>

                            <div class="stand-detail">
                                <span>STANDTYPE</span>
                                <strong>{{ $stand->StandType }}</strong>
                            </div>

                            <div class="stand-detail">
                                <span>PRIJS</span>
                                <strong>
                                    €{{ number_format($stand->Prijs, 2, ',', '.') }}
                                </strong>
                            </div>

                            <div class="stand-detail">
                                <span>VERKOPER</span>
                                <strong>
                                    @if ($stand->VerhuurdStatus && $stand->verkoper)
                                        {{ $stand->verkoper->Naam }}
                                    @else
                                        Nog niet verhuurd
                                    @endif
                                </strong>
                            </div>

                            <div class="stand-detail">
                                <span>CATEGORIE</span>
                                <strong>
                                    @if ($stand->VerhuurdStatus && $stand->verkoper)
                                        {{ $stand->verkoper->VerkooptSoort ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </div>

                        </div>

                        <!-- Reserveringsknoppen voor verkopers -->
                        @auth
                            @if (auth()->user()->rol === 'verkoper')

                                @if (!$stand->VerhuurdStatus)

                                    <!-- Happy Scenario -->
                                    <button type="button" class="save-stand-btn" style="display: block; width: 100%; margin-top: 20px;"
                                        onclick="openReserveerModal({{ $stand->Id }})">
                                        Reserveren
                                    </button>

                                @elseif (
                                        $stand->verkoper &&
                                        (int) $stand->verkoper->UserId === (int) auth()->id()
                                    )

                                    <!-- Unhappy Scenario: eigen reservering opnieuw proberen -->
                                    <form action="{{ route('stands.reserveren', ['stand' => $stand->Id]) }}" method="POST">
                                        @csrf

                                        <button type="submit" class="save-stand-btn" style="display: block; width: 100%; margin-top: 20px;">
                                            Reserveren
                                        </button>
                                    </form>

                                @else

                                    <!-- Een andere verkoper heeft deze stand -->
                                    <button type="button" class="save-stand-btn" disabled
                                        style="display: block; width: 100%; margin-top: 20px; opacity: 0.5; cursor: not-allowed;">
                                        Al gereserveerd
                                    </button>

                                @endif

                            @endif
                        @endauth

                    </article>

                @endforeach

            </section>

        @else

            <!-- Geen stands gevonden -->
            <section class="no-stands">
                <div class="no-stands-icon">!</div>

                <h2>GEEN STANDS BESCHIKBAAR</h2>

                <p>
                    Er zijn momenteel geen stands in het systeem.
                    Bekijk deze pagina later opnieuw.
                </p>
            </section>

        @endif

    </main>

    <!-- Pop-up voor een nieuwe reservering -->
    @auth
        @if (auth()->user()->rol === 'verkoper')

            <div id="reserveerModal" class="stand-modal" role="dialog" aria-modal="true" aria-labelledby="reserveerModalTitle"
                style="display: none;">

                <div class="stand-modal-content">

                    <button type="button" class="stand-modal-close" onclick="closeReserveerModal()" aria-label="Sluiten">
                        &times;
                    </button>

                    <h2 id="reserveerModalTitle">
                        STAND RESERVEREN
                    </h2>

                    <p>
                        Vul je contactgegevens in om deze stand te reserveren.
                    </p>

                    <form id="reserveerForm" method="POST">
                        @csrf

                        <div class="stand-form-group">
                            <label for="reserveerNaam">Naam *</label>

                            <input type="text" id="reserveerNaam" name="Naam" value="{{ old('Naam', auth()->user()->name) }}"
                                maxlength="100" required>
                        </div>

                        <div class="stand-form-group">
                            <label for="reserveerEmail">E-mailadres *</label>

                            <input type="email" id="reserveerEmail" name="Email"
                                value="{{ old('Email', auth()->user()->email) }}" maxlength="150" required>
                        </div>

                        <div class="stand-form-group">
                            <label for="reserveerTelefoon">
                                Telefoonnummer *
                            </label>

                            <input type="tel" id="reserveerTelefoon" name="Telefoonnummer" value="{{ old('Telefoonnummer') }}"
                                maxlength="20" required>
                        </div>

                        <button type="submit" class="save-stand-btn">
                            Reservering bevestigen
                        </button>

                    </form>

                </div>
            </div>

        @endif
    @endauth

    @include('partials.footer')

    <script>
        const reserveerModal = document.getElementById('reserveerModal');
        const reserveerForm = document.getElementById('reserveerForm');

        // Open het formulier voor de gekozen stand
        function openReserveerModal(standId) {
            if (!reserveerModal || !reserveerForm) {
                return;
            }

            reserveerForm.action =
                "{{ route('stands.reserveren', ['stand' => '__STAND_ID__']) }}"
                    .replace('__STAND_ID__', standId);

            reserveerModal.style.display = 'flex';
            document.getElementById('reserveerNaam').focus();
        }

        // Sluit de pop-up
        function closeReserveerModal() {
            if (reserveerModal) {
                reserveerModal.style.display = 'none';
            }
        }

        // Sluiten bij klikken buiten de pop-up
        if (reserveerModal) {
            reserveerModal.addEventListener('click', function (event) {
                if (event.target === reserveerModal) {
                    closeReserveerModal();
                }
            });
        }

        // Sluiten met Escape
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeReserveerModal();
            }
        });

        // Voorkom dubbel klikken tijdens het verzenden
        if (reserveerForm) {
            reserveerForm.addEventListener('submit', function () {
                const knop = reserveerForm.querySelector(
                    'button[type="submit"]'
                );

                knop.disabled = true;
                knop.textContent = 'Reservering verwerken...';
            });
        }
    </script>

</body>

</html>