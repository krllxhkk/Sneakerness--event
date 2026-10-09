
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

        <!-- Knop om een stand toe te voegen -->
        <button type="button" class="add-stand-btn" onclick="openStandModal()">
            Stand toevoegen
        </button>

        @if (session('success'))
            <p class="stand-success">{{ session('success') }}</p>
        @endif

        @if ($errors->has('stand'))
            <p class="stand-error">{{ $errors->first('stand') }}</p>
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
                                <span class="status rented">VERHUURD</span>
                            @else
                                <span class="status available">BESCHIKBAAR</span>
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
                                <strong>{{ $stand->Standnummer ?? '-' }}</strong>
                            </div>

                            <div class="stand-detail">
                                <span>LOCATIE</span>
                                <strong>{{ $stand->Locatie ?? '-' }}</strong>
                            </div>

                            <div class="stand-detail">
                                <span>STATUS</span>
                                <strong>
                                    {{ $stand->VerhuurdStatus ? 'Verhuurd' : 'Beschikbaar' }}
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
                    </article>

                @endforeach

            </section>

        @else

            <!-- Melding als er geen stands zijn -->
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

    <!-- Pop-up voor het toevoegen van een stand -->
    <div id="standModal" class="stand-modal"
        role="dialog" aria-modal="true"
        aria-labelledby="standModalTitle">

        <div class="stand-modal-content">

            <button type="button" class="stand-modal-close"
                onclick="closeStandModal()" aria-label="Sluiten">
                &times;
            </button>

            <h2 id="standModalTitle">STAND TOEVOEGEN</h2>

            <form id="standForm" action="{{ route('stands.store') }}" method="POST">
                @csrf

                <div class="stand-form-group">
                    <label for="standnummer">Standnummer *</label>

                    <input type="text" id="standnummer" name="Standnummer"
                        value="{{ old('Standnummer') }}" maxlength="20" required>

                    @error('Standnummer')
                        <span class="stand-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="stand-form-group">
                    <label for="standnaam">Standnaam *</label>

                    <input type="text" id="standnaam" name="Standnaam"
                        value="{{ old('Standnaam') }}" maxlength="100" required>

                    @error('Standnaam')
                        <span class="stand-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="stand-form-group">
                    <label for="locatie">Locatie *</label>

                    <input type="text" id="locatie" name="Locatie"
                        value="{{ old('Locatie') }}" maxlength="100" required>

                    @error('Locatie')
                        <span class="stand-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="stand-form-group">
                    <label for="prijs">Prijs (€) *</label>

                    <input type="number" id="prijs" name="Prijs"
                        value="{{ old('Prijs') }}"
                        min="0" max="999999.99" step="0.01" required>

                    @error('Prijs')
                        <span class="stand-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="stand-form-group">
                    <label for="kwaliteitsklasse">Kwaliteitsklasse</label>

                    <input type="text" id="kwaliteitsklasse"
                        name="Kwaliteitsklasse"
                        value="{{ old('Kwaliteitsklasse') }}" maxlength="30">

                    @error('Kwaliteitsklasse')
                        <span class="stand-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="save-stand-btn">
                    Opslaan
                </button>

            </form>

        </div>
    </div>

    @include('partials.footer')

    <script>
        const standModal = document.getElementById('standModal');
        const standForm = document.getElementById('standForm');

        // Open de pop-up met lege velden
        function openStandModal() {
            standForm.reset();

            standForm.querySelectorAll('input:not([type="hidden"])').forEach(input => {
                input.value = '';
            });

            // Verberg oude foutmeldingen
            standModal.querySelectorAll('.stand-field-error').forEach(fout => {
                fout.style.display = 'none';
            });

            standModal.style.display = 'flex';
            document.getElementById('standnummer').focus();
        }

        // Sluit de pop-up
        function closeStandModal() {
            standModal.style.display = 'none';
        }

        // Sluit de pop-up als je buiten het formulier klikt
        standModal.addEventListener('click', function(event) {
            if (event.target === standModal) {
                closeStandModal();
            }
        });

        // Sluit de pop-up met Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && standModal.style.display === 'flex') {
                closeStandModal();
            }
        });

        // Houd de pop-up open als er fouten zijn
        @if ($errors->any())
            standModal.style.display = 'flex';
        @endif
    </script>

</body>

</html>
