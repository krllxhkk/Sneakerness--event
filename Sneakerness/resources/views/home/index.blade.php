<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneakerness® Rotterdam</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Inter:wght@400;600;700;900&display=swap"
        rel="stylesheet">
</head>

<body>
<header>
    <div class="header-container">
        <div class="logo">SNEAKERNESS<sup>®</sup></div>

        <div class="hamburger" id="hamburgerBtn" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <ul class="nav-links" id="navLinks">
            <li><a href="#" class="active">Home</a></li>
            <li><a href="#event">Event</a></li>
            <li><a href="#tickets">Tickets</a></li>
            <li><a href="#verkopers">Verkopers</a></li>
            <li><a href="#stands">Stands</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>

        <div class="nav-right">
            <a href="#" class="login-link">LOGIN</a>
            <a href="#tickets" class="btn-yellow-sm">TICKET KOPEN</a>
        </div>
    </div>
</header>

</body>

</html>