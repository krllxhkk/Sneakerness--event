<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Stands | Sneakerness®</title>

    <link rel="stylesheet" href="{{ asset('css/stands.css') }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>

<main class="stands-container">

    <section class="stands-header">

        <span class="stands-label">
            SNEAKERNESS® STANDS
        </span>

        <h1>
            STANDS<br>
            OVERZICHT
        </h1>

        <p>
            Bekijk alle stands van Sneakerness® en zie welke stands
            beschikbaar of verhuurd zijn.
        </p>

    </section>


    {{-- HAPPY FLOW --}}
    @if ($stands->count() > 0)

        <section class="stands-grid">

            @foreach ($stands as $stand)

                <article class="stand-card">

                    <div class="stand-card-top">

                        <span class="stand-type">
                            {{ $stand->StandType }}
                        </span>

                        @if ($stand->VerhuurdStatus)

                            <span class="status rented">
                                VERHUURD
                            </span>

                        @else

                            <span class="status available">
                                BESCHIKBAAR
                            </span>

                        @endif

                    </div>


                    <h2>
                        {{ $stand->verkoper->Naam ?? 'Geen verkoper' }}
                    </h2>


                    <div class="stand-details">

                        <div class="stand-detail">
                            <span>SOORT</span>

                            <strong>
                                {{ $stand->verkoper->VerkooptSoort ?? '-' }}
                            </strong>
                        </div>

                        <div class="stand-detail">
                            <span>STANDTYPE</span>

                            <strong>
                                {{ $stand->StandType }}
                            </strong>
                        </div>

                        <div class="stand-detail">
                            <span>PRIJS</span>

                            <strong>
                                €{{ number_format($stand->Prijs, 2, ',', '.') }}
                            </strong>
                        </div>

                    </div>

                </article>

            @endforeach

        </section>


    {{-- UNHAPPY FLOW --}}
    @else

        <section class="no-stands">

            <div class="no-stands-icon">!</div>

            <h2>GEEN STANDS BESCHIKBAAR</h2>

            <p>
                Er zijn momenteel geen stands beschikbaar.
                Bekijk deze pagina later opnieuw.
            </p>

        </section>

    @endif

</main>

</body>
</html>