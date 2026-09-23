<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Stands | Sneakerness®</title>

    <!-- Algemene CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Stands CSS -->
    <link rel="stylesheet" href="{{ asset('css/stands.css') }}">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    @include('partials.navbar')


    <main class="stands-container">

        <!-- Header -->
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

                        <!-- Bovenkant van de kaart -->
                        <div class="stand-card-top">

                            <span class="stand-type">
                                STAND {{ $stand->StandType }}
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


                        <!-- Naam -->
                        <h2>

                            @if ($stand->VerhuurdStatus && $stand->verkoper)

                                {{ $stand->verkoper->Naam }}

                            @else

                                VRIJE STAND

                            @endif

                        </h2>


                        <!-- Informatie -->
                        <div class="stand-details">


                            <!-- Status -->
                            <div class="stand-detail">

                                <span>
                                    STATUS
                                </span>

                                <strong>
                                    @if ($stand->VerhuurdStatus)
                                        Verhuurd
                                    @else
                                        Beschikbaar
                                    @endif
                                </strong>

                            </div>


                            <!-- Standtype -->
                            <div class="stand-detail">

                                <span>
                                    STANDTYPE
                                </span>

                                <strong>
                                    {{ $stand->StandType }}
                                </strong>

                            </div>


                            <!-- Prijs -->
                            <div class="stand-detail">

                                <span>
                                    PRIJS
                                </span>

                                <strong>
                                    €{{ number_format($stand->Prijs, 2, ',', '.') }}
                                </strong>

                            </div>


                            <!-- Verkoper -->
                            <div class="stand-detail">

                                <span>
                                    VERKOPER
                                </span>

                                <strong>

                                    @if ($stand->VerhuurdStatus && $stand->verkoper)

                                        {{ $stand->verkoper->Naam }}

                                    @else

                                        Nog niet verhuurd

                                    @endif

                                </strong>

                            </div>


                            <!-- Categorie / soort -->
                            <div class="stand-detail">

                                <span>
                                    CATEGORIE
                                </span>

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


        {{-- UNHAPPY FLOW --}}
        @else

            <section class="no-stands">

                <div class="no-stands-icon">
                    !
                </div>

                <h2>
                    GEEN STANDS BESCHIKBAAR
                </h2>

                <p>
                    Er zijn momenteel geen stands in het systeem.
                    Bekijk deze pagina later opnieuw.
                </p>

            </section>

        @endif

    </main>


    <!-- Footer -->
    @include('partials.footer')

</body>

</html>