
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

        <!-- Titel van de pagina -->
        <section class="events-header">

            <span class="events-label">
                SNEAKERNESS® EVENTS
            </span>

            <h1>EVENTS<br>OVERZICHT</h1>

            <p>
                Bekijk alle aankomende Sneakerness® evenementen.
                Hier vind je de datum, locatie, beschikbare tickets
                en stands per evenement.
            </p>

        </section>


        <!-- Alleen de organisator ziet deze knop -->
        @auth
            @if (auth()->user()->email === 'organisator@sneakerness.nl')

                <button
                    type="button"
                    class="add-event-btn"
                    onclick="openEventModal()">
                    Event toevoegen
                </button>

            @endif
        @endauth


        <!-- Overzicht van alle evenementen -->
        @if ($events->count() > 0)

            <section class="events-grid">

                @foreach ($events as $event)

                    <article class="event-card">

                        <div class="event-card-top">

                            <span class="event-date">
                                {{ \Carbon\Carbon::parse($event->Datum)->format('d-m-Y') }}
                            </span>

                            <span class="event-status">
                                EVENT
                            </span>

                        </div>

                        <h2>{{ $event->Naam }}</h2>

                        <div class="event-details">

                            <!-- Locatie -->
                            <div class="event-detail">

                                <span class="detail-label">
                                    LOCATIE
                                </span>

                                <strong>{{ $event->Locatie }}</strong>

                            </div>


                            <!-- Tijd -->
                            <div class="event-detail">

                                <span class="detail-label">
                                    TIJD
                                </span>

                                <strong>
                                    {{ $event->Tijd ? substr($event->Tijd, 0, 5) : 'Niet ingesteld' }}
                                </strong>

                            </div>


                            <!-- Tickets -->
                            <div class="event-detail">

                                <span class="detail-label">
                                    TICKETS PER TIJDSLOT
                                </span>

                                <strong>
                                    {{ $event->AantalTicketsPerTijdslot }}
                                </strong>

                            </div>


                            <!-- Stands -->
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

            <!-- Als er geen evenementen zijn -->
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


    <!-- Alleen de organisator krijgt het formulier -->
    @auth
        @if (auth()->user()->email === 'organisator@sneakerness.nl')

            <!-- Event toevoegen pop-up -->
            <div
                id="eventModal"
                class="event-modal"
                role="dialog"
                aria-modal="true"
                aria-labelledby="eventModalTitle">

                <div class="event-modal-content">

                    <!-- Sluitknop -->
                    <button
                        type="button"
                        class="modal-close"
                        onclick="closeEventModal()"
                        aria-label="Sluiten">
                        &times;
                    </button>

                    <h2 id="eventModalTitle">
                        EVENT TOEVOEGEN
                    </h2>


                    <!-- Foutmeldingen verschijnen in de pop-up -->
                    @if ($errors->any())

                        <div class="event-errors" role="alert">

                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach

                        </div>

                    @endif


                    <!-- Formulier voor een nieuw evenement -->
                    <form action="{{ route('events.store') }}" method="POST">

                        @csrf


                        <!-- Naam -->
                        <div class="form-group">

                            <label for="naam">Naam</label>

                            <input
                                type="text"
                                id="naam"
                                name="Naam"
                                value="{{ old('Naam') }}"
                                maxlength="100"
                                required>

                        </div>


                        <!-- Datum -->
                        <div class="form-group">

                            <label for="datum">Datum</label>

                            <input
                                type="date"
                                id="datum"
                                name="Datum"
                                value="{{ old('Datum') }}"
                                min="{{ now()->format('Y-m-d') }}"
                                required>

                        </div>


                        <!-- Tijd -->
                        <div class="form-group">

                            <label for="tijd">Tijd</label>

                            <input
                                type="time"
                                id="tijd"
                                name="Tijd"
                                value="{{ old('Tijd') }}"
                                required>

                        </div>


                        <!-- Locatie -->
                        <div class="form-group">

                            <label for="locatie">Locatie</label>

                            <input
                                type="text"
                                id="locatie"
                                name="Locatie"
                                value="{{ old('Locatie') }}"
                                maxlength="150"
                                required>

                        </div>


                        <!-- Tickets per tijdslot -->
                        <div class="form-group">

                            <label for="tickets">
                                Tickets per tijdslot
                            </label>

                            <input
                                type="number"
                                id="tickets"
                                name="AantalTicketsPerTijdslot"
                                value="{{ old('AantalTicketsPerTijdslot', 0) }}"
                                min="0"
                                required>

                        </div>


                        <!-- Beschikbare stands -->
                        <div class="form-group">

                            <label for="stands">
                                Beschikbare stands
                            </label>

                            <input
                                type="number"
                                id="stands"
                                name="BeschikbareStands"
                                value="{{ old('BeschikbareStands', 0) }}"
                                min="0"
                                required>

                        </div>


                        <!-- Opslaan -->
                        <button type="submit" class="save-event-btn">
                            OPSLAAN
                        </button>

                    </form>

                </div>

            </div>

        @endif
    @endauth


    <!-- Succesmelding na het toevoegen -->
    @if (session('success'))

        <div
            id="successPopup"
            class="success-popup-overlay"
            role="status">

            <div class="success-popup">

                <div class="success-icon">
                    ✓
                </div>

                <h2>EVENT TOEGEVOEGD!</h2>

                <p>
                    {{ session('success') }}
                </p>

                <span>
                    Het evenement staat nu in het overzicht.
                </span>

            </div>

        </div>

    @endif


    @include('partials.footer')


    <script>

        // Open het formulier.
        function openEventModal() {

            const modal = document.getElementById('eventModal');

            if (modal) {
                modal.style.display = 'flex';
            }

        }


        // Sluit het formulier.
        function closeEventModal() {

            const modal = document.getElementById('eventModal');

            if (modal) {
                modal.style.display = 'none';
            }

        }


        document.addEventListener('DOMContentLoaded', function () {

            const eventModal = document.getElementById('eventModal');


            // Open het formulier opnieuw als er fouten zijn.
            @if ($errors->any())
                openEventModal();
            @endif


            // Controleer eerst of het formulier bestaat.
            if (eventModal) {

                // Sluit als je op de donkere achtergrond klikt.
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

            }


            // Laat de succesmelding automatisch verdwijnen.
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
