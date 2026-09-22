<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verkopers - Sneakerness® Rotterdam</title>

    <!-- Zelfde CSS als de homepagina -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Inter:wght@400;600;700;900&display=swap"
        rel="stylesheet">
</head>

<body>

    <!-- NAVIGATIEBALK -->
    <header>
        <div class="header-container">

            <!-- Logo -->
            <div class="logo">
                SNEAKERNESS<sup>®</sup>
            </div>

            <!-- Hamburger menu -->
            <div class="hamburger" id="hamburgerBtn" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <!-- Navigatielinks -->
            <ul class="nav-links" id="navLinks">
                <li>
                    <a href="{{ route('home') }}">Home</a>
                </li>

                <li>
                    <a href="{{ route('home') }}#event">Event</a>
                </li>

                <li>
                    <a href="{{ route('home') }}#tickets">Tickets</a>
                </li>

                <li>
                    <a href="{{ route('verkopers.index') }}" class="active">
                        Verkopers
                    </a>
                </li>

                <li>
                    <a href="{{ route('home') }}#stands">Stands</a>
                </li>

                <li>
                    <a href="{{ route('home') }}#contact">Contact</a>
                </li>
            </ul>

            <!-- Rechterkant van de navigatie -->
            <div class="nav-right">
                <a href="#" class="login-link">LOGIN</a>

                <a href="{{ route('home') }}#tickets" class="btn-yellow-sm">
                    TICKET KOPEN
                </a>
            </div>

        </div>
    </header>


    <!-- VERKOPERS PAGINA -->
    <main class="verkopers-page">

        <!-- Pagina introductie -->
        <section class="verkopers-header">

            <span class="sub-label">
                VERKOPERS • SNEAKERNESS® ROTTERDAM
            </span>

            <h1>
                ONTDEK ONZE<br>
                <span class="highlight">VERKOPERS</span>
            </h1>

            <p>
                Bekijk de verkopers die deelnemen aan Sneakerness® Rotterdam.
                Ontdek welke producten en stands tijdens het evenement aanwezig zijn.
            </p>

        </section>


        <!-- Overzicht van verkopers -->
        <section class="verkopers-overzicht">

            @forelse($verkopers as $verkoper)

                <!-- Kaart van een verkoper -->
                <article class="verkoper-card">

                    <!-- Naam -->
                    <h2>{{ $verkoper->Naam }}</h2>

                    <!-- Gegevens -->
                    <div class="verkoper-info">

                        <div>
                            <span>VERKOOPT</span>
                            <p>{{ $verkoper->VerkooptSoort }}</p>
                        </div>

                        <div>
                            <span>STAND TYPE</span>
                            <p>{{ $verkoper->StandType }}</p>
                        </div>

                        <div>
                            <span>DAGEN</span>
                            <p>{{ $verkoper->Dagen }}</p>
                        </div>

                        <div>
                            <span>STATUS</span>
                            <p>{{ $verkoper->SpecialeStatus ?? 'Geen' }}</p>
                        </div>

                    </div>

                    <!-- Opmerking indien aanwezig -->
                    @if($verkoper->Opmerking)
                        <div class="verkoper-opmerking">
                            {{ $verkoper->Opmerking }}
                        </div>
                    @endif

                </article>

            @empty

                <!-- Geen verkopers gevonden -->
                <div class="no-verkopers">
                    <h2>GEEN VERKOPERS GEVONDEN</h2>
                    <p>
                        Er zijn momenteel geen actieve verkopers beschikbaar.
                    </p>
                </div>

            @endforelse

        </section>
     <!-- Overzicht van contactpersonen -->
        <section class="contactpersonen-section">

            <!-- Titel van de tabel -->
            <span class="sub-label">
                STANDHOUDERS
            </span>

            <h2>CONTACTPERSONEN</h2>

            <!-- Tabel met contactpersonen -->
            <div class="contactpersonen-table-wrapper">

                <table class="contactpersonen-table">

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

                        <!-- Toon alle contactpersonen -->
                        @foreach($contactpersonen as $contactpersoon)

                            <tr>

                                <!-- Naam -->
                                <td class="contactpersoon-naam">

                                    <span class="contactpersoon-icon">
                                        {{ strtoupper(substr($contactpersoon->Naam, 0, 1)) }}
                                    </span>

                                    {{ $contactpersoon->Naam }}

                                </td>

                                <!-- Verkoper -->
                                <td>
                                    {{ $contactpersoon->VerkoperNaam ?? '-' }}
                                </td>

                                <!-- E-mailadres -->
                                <td>
                                    {{ $contactpersoon->{'E-mailadres'} }}
                                </td>

                                <!-- Telefoonnummer -->
                                <td>
                                    {{ $contactpersoon->Telefoonnummer }}
                                </td>

                                <!-- Status -->
                                <td class="status-actief">
                                    <span>●</span> Actief
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </section>
    </main>


    <!-- FOOTER -->
    <footer>
        <div class="footer-container">

            <div class="footer-left">
                <strong class="logo">
                    SNEAKERNESS<sup>®</sup>
                </strong>

                <p>Rotterdam | Van Nellefabriek</p>
            </div>

            <ul class="footer-links">
                <li>
                    <a href="{{ route('home') }}">Home</a>
                </li>

                <li>
                    <a href="{{ route('home') }}#event">Event</a>
                </li>

                <li>
                    <a href="{{ route('home') }}#tickets">Tickets</a>
                </li>

                <li>
                    <a href="{{ route('home') }}#contact">Contact</a>
                </li>
            </ul>

            <div class="footer-right">
                <p>© Sneakerness®</p>
            </div>

        </div>
    </footer>

    <script>
        /**
         * Opent en sluit het mobiele menu.
         */
        function toggleMenu() {
            const navLinks = document.getElementById('navLinks');

            navLinks.classList.toggle('active');
        }
    </script>

</body>

</html>