
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ticket toevoegen | Sneakerness®</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/organisator-tickets.css') }}">
</head>

<body>

    <!-- Bovenste navigatie -->
    <header class="dashboard-header">
        <div class="dashboard-logo">
            SNEAKERNESS<span>®</span>
        </div>

        <nav class="dashboard-nav">
            <a href="{{ route('organisator.dashboard') }}">Dashboard</a>
            <a href="{{ route('events.index') }}">Evenementen</a>
            <a href="{{ route('organisator.tickets.index') }}" class="actief">Tickets</a>
            <a href="{{ route('verkopers.index') }}">Verkopers</a>
            <a href="{{ route('stands.index') }}">Stands</a>
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="uitloggen-formulier">
            @csrf
            <button type="submit" class="uitloggen-knop">UITLOGGEN</button>
        </form>
    </header>

    <div class="dashboard-layout">

        <!-- Zijbalk -->
        <aside class="zijbalk">
            <p class="zijbalk-label">Organisator</p>
            <h2>Beheer Panel</h2>

            <nav class="beheer-menu">
                <a href="{{ route('organisator.dashboard') }}">Dashboard</a>
                <a href="{{ route('events.index') }}">Evenementen</a>
                <a href="{{ route('organisator.tickets.index') }}" class="actief">Tickets</a>
                <a href="{{ route('verkopers.index') }}">Verkopers</a>
                <a href="{{ route('stands.index') }}">Stands</a>
            </nav>
        </aside>

        <!-- Ticketformulier -->
        <main class="dashboard-inhoud">

            <div class="ticket-formulier-pagina">

                <a href="{{ route('organisator.tickets.index') }}" class="ticket-terug-link">
                    ← Terug naar tickets
                </a>

                <h1>TICKET TOEVOEGEN</h1>

                <p class="ticket-formulier-omschrijving">
                    Vul de gegevens in om een nieuw ticket toe te voegen.
                </p>

                <section class="ticket-formulier-kaart">

                    <h2>TICKETGEGEVENS</h2>

                    <!-- Databasefout -->
                    @if (session('error'))
                        <div class="ticket-formulier-fout" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Validatiefouten -->
                    @if ($errors->any())
                        <div class="ticket-formulier-fout" role="alert">
                            <p>Controleer de ingevulde gegevens:</p>

                            <ul>
                                @foreach ($errors->all() as $fout)
                                    <li>{{ $fout }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('organisator.tickets.store') }}">
                        @csrf

                        <!-- Datum -->
                        <div class="ticket-formulier-groep">
                            <label for="datum">Datum</label>

                            <input
                                type="date"
                                id="datum"
                                name="datum"
                                value="{{ old('datum') }}"
                                min="{{ date('Y-m-d') }}"
                                required
                            >
                        </div>

                        <!-- Tijdslot -->
                        <div class="ticket-formulier-groep">
                            <label for="tijdslot">Tijdslot</label>

                            <select id="tijdslot" name="tijdslot" required>
                                <option value="">Kies een tijdslot</option>

                                @foreach (['11:00', '12:00', '14:00', '16:00'] as $tijd)
                                    <option value="{{ $tijd }}" @selected(old('tijdslot') === $tijd)>
                                        {{ $tijd }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Prijs -->
                        <div class="ticket-formulier-groep">
                            <label for="tarief">Prijs (€)</label>

                            <input
                                type="number"
                                id="tarief"
                                name="tarief"
                                min="0"
                                max="999999.99"
                                step="0.01"
                                value="{{ old('tarief') }}"
                                placeholder="Bijvoorbeeld 25.00"
                                required
                            >
                        </div>

                        <!-- Aantal beschikbare tickets -->
                        <div class="ticket-formulier-groep">
                            <label for="aantal_tickets_per_tijdslot">
                                Aantal beschikbare tickets
                            </label>

                            <input
                                type="number"
                                id="aantal_tickets_per_tijdslot"
                                name="aantal_tickets_per_tijdslot"
                                min="1"
                                max="2147483647"
                                step="1"
                                value="{{ old('aantal_tickets_per_tijdslot') }}"
                                placeholder="Bijvoorbeeld 100"
                                required
                            >
                        </div>

                        <!-- Actieknoppen -->
                        <div class="ticket-formulier-knoppen">
                            <a href="{{ route('organisator.tickets.index') }}"
                               class="ticket-annuleren-knop">
                                ANNULEREN
                            </a>

                            <button type="submit" class="ticket-opslaan-knop">
                                TICKET OPSLAAN
                            </button>
                        </div>

                    </form>
                </section>
            </div>

        </main>
    </div>

</body>
</html>
