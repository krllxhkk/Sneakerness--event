<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneakerness® Rotterdam</title>



    <!-- CSS stylesheet uit de public/css map laden -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/events.css') }}">
    <link rel="stylesheet" href="{{ asset('css/stands.css') }}">
    <!-- Google Fonts voor de strakke typografie -->
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Inter:wght@400;600;700;900&display=swap"
        rel="stylesheet">
</head>

<body>

    <!-- NAVIGATIEBALK -->
    <header>
        <div class="header-container">
            <!-- Logo van het evenement -->
            <div class="logo">SNEAKERNESS<sup>®</sup></div>

            <!-- Hamburger Menu Icoon (Zichtbaar op mobiel/klein scherm) -->
            <div class="hamburger" id="hamburgerBtn" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <!-- Navigatielinks -->
            <ul class="nav-links" id="navLinks">
                <li><a href="#" class="active">Home</a></li>
                <li><a href="{{ route('events.index') }}">Events</a></li>
                <li><a href="{{ route('tickets.index') }}"
                        class="{{ request()->routeIs('tickets.index') ? 'active' : '' }}">Tickets</a></li>
                <li><a href="{{ route('verkopers.index') }}">Verkopers</a></li>
                <li><a href="{{ route('stands.index') }}">Stands</a></li>
                <li><a href="#tickets">Contact</a></li>
            </ul>

            <!-- Rechterkant van de navigatie (Login & Actieknop) -->
            <div class="nav-right">
                <a href="#" class="login-link">LOGIN</a>
                <a href="#tickets" class="btn-yellow-sm">TICKET KOPEN</a>
            </div>
        </div>
    </header>
    @if (!empty($errorMessage))
        <div class="alert alert-danger text-center my-4"
            style="background-color: #2b0000; color: #ff4d4d; border: 1px solid #ff4d4d; padding: 15px; border-radius: 6px; font-weight: bold; max-width: 1200px; margin: 90px auto 20px auto; position: relative; z-index: 999;">
            {{ $errorMessage }}
        </div>
    @endif

    <!-- 1. HERO BANNER SECTIE -->
    <!-- HERO SECTIE DIREK IN HET MIDDEN (NIET BINNEN EEN SMALLE CONTAINER) -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <span class="badge-pill">• NOVEMBER • VAN NELLEFABRIEK • ROTTERDAM</span>
                <h1 class="hero-title">SNEAK<br><span class="highlight">ERNESS<sup>®</sup></span><br>ROTTERDAM</h1>
                <p class="hero-tags">ART • SPORT • FASHION • MUSIC • SNEAKERS</p>
                <p class="hero-description">
                    Ontdek de wereld van sneakers tijdens het grootste sneakerevenement van Nederland — in de
                    iconische Van Nellefabriek in Rotterdam.
                </p>
                <div class="hero-buttons">
                    <a href="#tickets" class="btn-yellow-lg">TICKETS KOPEN</a>
                    <a href="#stands" class="btn-outline-lg">STAND HUREN</a>
                </div>
            </div>
        </div>
        <div class="scroll-indicator">
            <span>SCROLL</span>
            <div class="scroll-line"></div>
        </div>
    </section>

    <!-- REST VAN DE CONTENT IN CONTAINER -->
    <main class="container">
        <!-- Overige secties... -->
    </main>
    <!-- METADATA STRIP ONDER HERO -->
    <div class="meta-strip">
        <div class="meta-item"><span>LOCATIE</span><strong>VAN NELLEFABRIEK</strong></div>
        <div class="meta-item"><span>STAD</span><strong>ROTTERDAM</strong></div>
        <div class="meta-item"><span>DATUM</span><strong>14 - 15 NOVEMBER</strong></div>
        <div class="meta-item"><span>COMMUNITY</span><strong>SNEAKERS • ART • FASHION • MUSIC</strong></div>
    </div>

    <!-- 2. OVER HET EVENEMENT SECTIE -->
    <section id="event" class="about-section">
        <div class="about-container">
            <div class="about-text">
                <span class="sub-label">OVER HET EVENEMENT</span>
                <h2>MEER DAN<br>ALLEEN<br><span class="highlight">SNEAKERS</span></h2>
                <p>
                    Sneakerness® Rotterdam is het ontmoetingspunt voor de sneaker- en streetwear-community. Twee
                    dagen
                    lang staan de deuren van de Van Nellefabriek open voor iedereen die gepassioneerd is door
                    sneakers,
                    kunst, muziek en mode.
                </p>
                <p>
                    Van exclusieve drops tot live art, van barbershops tot DJ-sets — Sneakerness® is meer dan een
                    beurs.
                    Het is een cultureel evenement.
                </p>

                <!-- Statistieken teller blokjes -->
                <div class="stats-grid">
                    <div class="stat-box">
                        <span class="stat-number">200+</span>
                        <span class="stat-label">VERKOPERS</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">10K+</span>
                        <span class="stat-label">BEZOEKERS</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">2</span>
                        <span class="stat-label">DAGEN</span>
                    </div>
                </div>
            </div>

            <div class="about-image-wrapper">
                <img src="https://images.unsplash.com/photo-1552346154-21d32810aba3?q=80&w=1000&auto=format&fit=crop"
                    alt="Sneakers Event" class="about-img">
                <div class="image-badge">
                    <strong>VAN NELLEFABRIEK</strong>
                    <span>Rotterdam, Nederland</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. DE ERVARING / CATEGORIEËN SECTIE -->
    <section class="experience-section">
        <span class="sub-label text-center">WAT JE KAN VERWACHTEN</span>
        <h2 class="section-heading text-center">DE SNEAKERNESS<sup>®</sup> ERVARING</h2>

        <div class="categories-grid">
            <div class="category-card">
                <div class="cat-icon">👟</div>
                <span>SNEAKERS</span>
            </div>
            <div class="category-card">
                <div class="cat-icon">🎨</div>
                <span>ART</span>
            </div>
            <div class="category-card">
                <div class="cat-icon">⚽</div>
                <span>SPORT</span>
            </div>
            <div class="category-card">
                <div class="cat-icon">👔</div>
                <span>FASHION</span>
            </div>
            <div class="category-card">
                <div class="cat-icon">🎵</div>
                <span>MUSIC</span>
            </div>
            <div class="category-card">
                <div class="cat-icon">🤝</div>
                <span>COMMUNITY</span>
            </div>
        </div>
    </section>

    <!-- 4. DYNAMISCHE TICKETS SECTIE (DATA UIT DATABASE) -->
    <section id="tickets" class="tickets-section">
        <div class="tickets-header-row">
            <div>
                <span class="sub-label">ENTREEPRIJZEN</span>
                <h2 class="section-heading">TICKETS</h2>
            </div>
            <a href="#" class="btn-outline-yellow">BEKIJK ALLE TICKETS</a>
        </div>

        <div class="tickets-grid">

            <!-- KOLOM: ZATERDAG TICKETS -->
            <div class="day-column">
                <h3 class="day-title">
                    <span class="dot">•</span> ZATERDAG
                    <span class="day-date">
                        <!-- Haalt de datum op van het eerste ticket en formatteert deze netjes (bijv. 26 September 2026) -->
                        {{ !empty($ticketsZaterdag) ? \Carbon\Carbon::parse(reset($ticketsZaterdag)->datum)->format('j F Y') : '' }}
                    </span>
                </h3>

                <!-- Doorloop alle actieve zaterdagtickets uit de database -->
                @forelse($ticketsZaterdag as $ticket)
                    <div class="ticket-card">
                        <div class="ticket-left">
                            <span class="ticket-time">{{ substr($ticket->tijdslot, 0, 5) }}</span>
                            <div class="ticket-sub">
                                <span class="ingang-tag">INGANG</span>
                                <span class="ticket-count">{{ $ticket->aantal_tickets_per_tijdslot }} tickets
                                    beschikbaar</span>
                            </div>
                        </div>
                        <div class="ticket-right">
                            <span class="ticket-price">€{{ number_format($ticket->tarief, 0) }}</span>
                            <span class="pp-label">p.p.</span>
                        </div>
                    </div>
                @empty
                    <p class="no-tickets">Geen komende tickets beschikbaar voor zaterdag.</p>
                @endforelse
            </div>

            <!-- KOLOM: ZONDAG TICKETS -->
            <div class="day-column">
                <h3 class="day-title">
                    <span class="dot">•</span> ZONDAG
                    <span class="day-date">
                        {{ !empty($ticketsZondag) ? \Carbon\Carbon::parse(reset($ticketsZondag)->datum)->format('j F Y') : '' }}
                    </span>
                </h3>

                <!-- Doorloop alle actieve zondagtickets uit de database -->
                @forelse($ticketsZondag as $ticket)
                    <div class="ticket-card">
                        <div class="ticket-left">
                            <span class="ticket-time">{{ substr($ticket->tijdslot, 0, 5) }}</span>
                            <div class="ticket-sub">
                                <span class="ingang-tag">INGANG</span>
                                <span class="ticket-count">{{ $ticket->aantal_tickets_per_tijdslot }} tickets
                                    beschikbaar</span>
                            </div>
                        </div>
                        <div class="ticket-right">
                            <span class="ticket-price">€{{ number_format($ticket->tarief, 0) }}</span>
                            <span class="pp-label">p.p.</span>
                        </div>
                    </div>
                @empty
                    <p class="no-tickets">Geen komende tickets beschikbaar voor zondag.</p>
                @endforelse
            </div>

        </div>

        <div class="text-center margin-top-40">
            <a href="#tickets" class="btn-yellow-lg">TICKETS KOPEN</a>
        </div>
    </section>

    <!-- 5. GELE STAND BANNER SECTIE -->
    <section id="stands" class="yellow-banner-section">
        <h2>HUUR JOUW STAND</h2>
        <p>Presenteer jouw merk, sneakers of diensten aan duizenden bezoekers. Beschikbare stands zijn beperkt.</p>
        <div class="banner-buttons">
            <a href="#" class="btn-black">STAND HUREN</a>
            <a href="#" class="btn-outline-dark">BEKIJK STANDS</a>
        </div>
    </section>

    <!-- 6. LOCATIE SECTIE -->
    <section class="location-section">
        <div class="location-container">
            <div class="location-image">
                <!-- Vang hier de foto op met een eigen afbeelding of nieuwe URL -->
                <img src="https://images.unsplash.com/photo-1511556532299-8f662fc26c06?q=80&w=1000&auto=format&fit=crop"
                    alt="Sneakerness Event">
            </div>
            <div class="location-info">
                <span class="sub-label">LOCATIE</span>
                <h2>VAN NELLE<br>FABRIEK</h2>
                <p>
                    De iconische Van Nellefabriek in Rotterdam is een UNESCO Werelderfgoedsite en het perfecte decor
                    voor Sneakerness®. De industriële architectuur biedt een unieke sfeer die kunst, cultuur en
                    community bij elkaar brengt.
                </p>
                <ul class="location-list">
                    <li>📍 Van Nelleweg 1, Rotterdam</li>
                    <li>🚌 Goed bereikbaar met OV</li>
                    <li>🅿️ Gratis parkeren beschikbaar</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- 7. FOOTER -->
    <footer>
        <div class="footer-container">
            <div class="footer-left">
                <strong class="logo">SNEAKERNESS<sup>®</sup></strong>
                <p>Rotterdam | Van Nellefabriek</p>
            </div>
            <ul class="footer-links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('events.index') }}">Event</a></li>
                <li><a href="{{ route('tickets.index') }}">Tickets</a></li>
                <li><a href="{{ route('contactpersonen.index') }}">Contact</a></li>
            </ul>
            <div class="footer-right">
                <p>© Sneakerness®</p>
            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT VOOR HAMBURGER MENU -->
    <script>
        /**
         * Schakelt de klasse 'active' op de navigatielinks in/uit
         * wanneer op de hamburgerknop op mobiel wordt geklikt.
         */
        function toggleMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }
    </script>

</body>

</html>
