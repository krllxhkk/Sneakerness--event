<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneakerness® Rotterdam</title>

    <!-- CSS stylesheet uit de public/css map laden -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
                <li><a href="#event">Event</a></li>
                <li><a href="#tickets">Tickets</a></li>
                <li><a href="#verkopers">Verkopers</a></li>
                <li><a href="#stands">Stands</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>

            <!-- Rechterkant van de navigatie (Login & Actieknop) -->
            <div class="nav-right">
                <a href="#" class="login-link">LOGIN</a>
                <a href="#tickets" class="btn-yellow-sm">TICKET KOPEN</a>
            </div>
        </div>
    </header>

    <!-- 1. HERO BANNER SECTIE -->
    <<!-- HERO SECTIE DIREK IN HET MIDDEN (NIET BINNEN EEN SMALLE CONTAINER) -->
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

</body>

</html>