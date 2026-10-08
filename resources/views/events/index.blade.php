<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Events | Sneakerness®</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/events.css') }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    @include('partials.navbar')

    <main class="events-container">

        <!-- EVENTS HEADER -->
        <section class="events-header">
            <span class="events-label">SNEAKERNESS® EVENTS</span>

            <h1>EVENTS<br>OVERZICHT</h1>

            <p>
                Bekijk alle aankomende Sneakerness® evenementen.
                Hier vind je de datum, locatie, beschikbare tickets
                en stands per evenement.
            </p>
        </section>

        <!-- EVENT TOEVOEGEN -->
        <button type="button" class="add-event-btn" onclick="openEventModal()">
            Event toevoegen
        </button>

        <!-- EVENEMENTEN OVERZICHT -->
        @if ($events->count() > 0)

            <section class="events-grid">

                @foreach ($events as $event)

                    <article class="event-card">

                        <div class="event-card-top">
                            <span class="event-date">
                                {{ \Carbon\Carbon::parse($event->Datum)->format('d-m-Y') }}
                            </span>

                            <span class="event-status">EVENT</span>
                        </div>

                        <h2>{{ $event->Naam }}</h2>

                        <div class="event-details">

                            <div class="event-detail">
                                <span class="detail-label">LOCATIE</span>
                                <strong>{{ $event->Locatie }}</strong>
                            </div>

                            <div class="event-detail">
                                <span class="detail-label">TIJD</span>
                                <strong>
                                    {{ $event->Tijd ? substr($event->Tijd, 0, 5) : 'Niet ingesteld' }}
                                </strong>
                            </div>

                            <div class="event-detail">
                                <span class="detail-label">
                                    TICKETS PER TIJDSLOT
                                </span>

                                <strong>
                                    {{ $event->AantalTicketsPerTijdslot }}
                                </strong>
                            </div>

                            <div class="event-detail">
                                <span class="detail-label">
                                    BESCHIKBARE STANDS
                                </span>

                                <strong>
                                    {{ $event->BeschikbareStands }}
                                </strong>
                            </div>

                        </div>

                    </article>

                @endforeach

            </section>

        @else

            <section class="no-events">
                <div class="no-events-icon">!</div>

                <h2>GEEN EVENTS BESCHIKBAAR</h2>

                <p>
                    Er zijn momenteel geen evenementen beschikbaar.
                    Bekijk deze pagina later opnieuw.
                </p>
            </section>

        @endif

    </main>

    <!-- =====================================
         EVENT TOEVOEGEN POP-UP
    ====================================== -->

    <div id="eventModal" class="event-modal" role="dialog" aria-modal="true" aria-labelledby="eventModalTitle">

        <div class="event-modal-content">

            <!-- SLUITKNOP -->
            <button type="button" class="modal-close" onclick="closeEventModal()" aria-label="Sluiten">
                &times;
            </button>

            <h2 id="eventModalTitle">EVENT TOEVOEGEN</h2>

            <!-- FOUTMELDING BINNEN POP-UP -->
            @if ($errors->any())
                <div class="event-errors" role="alert">

                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach

                </div>
            @endif

            <!-- FORMULIER -->
            <form action="{{ route('events.store') }}" method="POST">

                @csrf

                <!-- NAAM -->
                <div class="form-group">
                    <label for="naam">Naam</label>

                    <input type="text" id="naam" name="Naam" value="{{ old('Naam') }}" maxlength="100" required>
                </div>

                <!-- DATUM -->
                <div class="form-group">
                    <label for="datum">Datum</label>

                    <input type="date" id="datum" name="Datum" value="{{ old('Datum') }}"
                        min="{{ now()->format('Y-m-d') }}" required>
                </div>

                <!-- TIJD -->
                <div class="form-group">
                    <label for="tijd">Tijd</label>

                    <input type="time" id="tijd" name="Tijd" value="{{ old('Tijd') }}" required>
                </div>

                <!-- LOCATIE -->
                <div class="form-group">
                    <label for="locatie">Locatie</label>

                    <input type="text" id="locatie" name="Locatie" value="{{ old('Locatie') }}" maxlength="150"
                        required>
                </div>


                <!-- Tickets per tijdslot -->
                <div class="form-group">
                    <label for="tickets">Tickets per tijdslot</label>

                    <input type="number" id="tickets" name="AantalTicketsPerTijdslot"
                        value="{{ old('AantalTicketsPerTijdslot', 0) }}" min="0" required>
                </div>

                <!-- Beschikbare stands -->
                <div class="form-group">
                    <label for="stands">Beschikbare stands</label>

                    <input type="number" id="stands" name="BeschikbareStands" value="{{ old('BeschikbareStands', 0) }}"
                        min="0" required>
                </div>


                <!-- OPSLAAN -->
                <button type="submit" class="save-event-btn">
                    OPSLAAN
                </button>

            </form>

        </div>
    </div>

    <!-- =====================================
         SUCCES POP-UP
    ====================================== -->

    @if (session('success'))

        <div id="successPopup" class="success-popup-overlay" role="status">

            <div class="success-popup">

                <div class="success-icon">✓</div>

                <h2>EVENT TOEGEVOEGD!</h2>

                <p>{{ session('success') }}</p>

                <span>
                    Het evenement staat nu in het overzicht.
                </span>

            </div>

        </div>

    @endif

    @include('partials.footer')

    <!-- =====================================
         JAVASCRIPT
    ====================================== -->

    <script>

        // Open het formulier.
        function openEventModal() {
            const modal = document.getElementById('eventModal');

            modal.style.display = 'flex';
        }

        // Sluit het formulier.
        function closeEventModal() {
            const modal = document.getElementById('eventModal');

            modal.style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {

            const eventModal = document.getElementById('eventModal');

            // Bij validatiefouten wordt de pop-up
            // automatisch opnieuw geopend.
            @if ($errors->any())
                openEventModal();
            @endif

            // Sluit het formulier als de gebruiker
            // op de donkere achtergrond klikt.
            eventModal.addEventListener('click', function (event) {

                if (event.target === eventModal) {
                    closeEventModal();
                }

            });

            // Sluit het formulier met Escape.
            document.addEventListener('keydown', function (event) {

                if (event.key === 'Escape') {
                    closeEventModal();
                }

            });

            // Succesmelding automatisch verwijderen.
            const successPopup = document.getElementById('successPopup');

            if (successPopup) {

                setTimeout(function () {

                    successPopup.style.transition = 'opacity 0.3s ease';
                    successPopup.style.opacity = '0';

                    setTimeout(function () {
                        successPopup.remove();
                    }, 300);

                }, 2500);

            }

        });

    </script>

</body>

</html>