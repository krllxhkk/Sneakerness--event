
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

        <section class="events-header">
            <span class="events-label">SNEAKERNESS® EVENTS</span>

            <h1>EVENTS<br>OVERZICHT</h1>

            <p>
                Bekijk alle aankomende Sneakerness® evenementen.
                Hier vind je de datum, locatie, beschikbare tickets
                en stands per evenement.
            </p>
        </section>

        <button type="button" class="add-event-btn" onclick="openEventModal()">
            Event toevoegen
        </button>

        @if (session('success'))
            <p class="event-success">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <div class="event-errors">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

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
                                <span class="detail-label">TICKETS PER TIJDSLOT</span>
                                <strong>{{ $event->AantalTicketsPerTijdslot }}</strong>
                            </div>

                            <div class="event-detail">
                                <span class="detail-label">BESCHIKBARE STANDS</span>
                                <strong>{{ $event->BeschikbareStands }}</strong>
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

    <div id="eventModal" class="event-modal">

        <div class="event-modal-content">

            <button type="button" class="modal-close" onclick="closeEventModal()">
                &times;
            </button>

            <h2>EVENT TOEVOEGEN</h2>

            <form action="{{ route('events.store') }}" method="POST">

                @csrf

                <div class="form-group">
                    <label for="naam">Naam</label>
                    <input type="text" id="naam" name="Naam"
                        value="{{ old('Naam') }}" required>
                </div>

                <div class="form-group">
                    <label for="datum">Datum</label>
                    <input type="date" id="datum" name="Datum"
                        value="{{ old('Datum') }}" required>
                </div>


                <div class="form-group">
                    <label for="locatie">Locatie</label>
                    <input type="text" id="locatie" name="Locatie"
                        value="{{ old('Locatie') }}" required>
                </div>

                <button type="submit" class="save-event-btn">
                    Opslaan
                </button>

            </form>

        </div>

    </div>

    @include('partials.footer')

    <script>
        function openEventModal() {
            document.getElementById('eventModal').style.display = 'flex';
        }

        function closeEventModal() {
            document.getElementById('eventModal').style.display = 'none';
        }

        @if ($errors->any())
            openEventModal();
        @endif
    </script>

</body>

</html>
