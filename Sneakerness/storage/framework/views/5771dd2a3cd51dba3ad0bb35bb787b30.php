<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contactpersonen - Sneakerness® Rotterdam</title>

    <!-- Zelfde CSS als de andere pagina's -->
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Inter:wght@400;600;700;900&display=swap"
        rel="stylesheet">
</head>

<body>

    <!-- Navigatiebalk -->
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
                <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
                <li><a href="<?php echo e(route('home')); ?>#event">Event</a></li>
                <li><a href="<?php echo e(route('home')); ?>#tickets">Tickets</a></li>
                <li><a href="<?php echo e(route('verkopers.index')); ?>">Verkopers</a></li>
                <li><a href="<?php echo e(route('home')); ?>#stands">Stands</a></li>
                <li><a href="<?php echo e(route('home')); ?>#contact">Contact</a></li>
            </ul>

            <!-- Rechterkant van de navigatie -->
            <div class="nav-right">
                <a href="#" class="login-link">LOGIN</a>
                <a href="<?php echo e(route('home')); ?>#tickets" class="btn-yellow-sm">
                    TICKET KOPEN
                </a>
            </div>

        </div>
    </header>

    <!-- Contactpersonen pagina -->
    <main class="contactpersonen-page">

        <!-- Pagina introductie -->
        <section class="contactpersonen-header">

            <span class="sub-label">
                CONTACTPERSONEN • SNEAKERNESS® ROTTERDAM
            </span>

            <h1>
                OVERZICHT VAN<br>
                <span class="highlight">CONTACTPERSONEN</span>
            </h1>

            <p>
                Bekijk de contactpersonen van de verkopers die deelnemen
                aan Sneakerness® Rotterdam.
            </p>

        </section>

        <!-- Contactpersonen overzicht -->
        <section class="contactpersonen-section">

            <!-- Tabel -->
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

                        
                        <?php $__empty_1 = true; $__currentLoopData = $contactpersonen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contactpersoon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <tr>

                                <!-- Naam -->
                                <td class="contactpersoon-naam">
                                    <span class="contactpersoon-icon">
                                        <?php echo e(strtoupper(substr($contactpersoon->Naam, 0, 1))); ?>

                                    </span>

                                    <?php echo e($contactpersoon->Naam); ?>

                                </td>

                                <!-- Verkoper -->
                                <td>
                                    <?php echo e($contactpersoon->VerkoperNaam); ?>

                                </td>

                                <!-- E-mailadres -->
                                <td>
                                    <?php echo e($contactpersoon->{'E-mailadres'}); ?>

                                </td>

                                <!-- Telefoonnummer -->
                                <td>
                                    <?php echo e($contactpersoon->Telefoonnummer); ?>

                                </td>

                                <!-- Status -->
                                <td class="status-actief">
                                    <span>●</span> Actief
                                </td>

                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <!-- Geen contactpersonen -->
                            <tr>
                                <td colspan="5" class="contactpersonen-empty">
                                    Geen actieve contactpersonen gevonden.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-container">

            <div class="footer-left">
                <strong class="logo">
                    SNEAKERNESS<sup>®</sup>
                </strong>

                <p>Rotterdam | Van Nellefabriek</p>
            </div>

            <ul class="footer-links">
                <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
                <li><a href="<?php echo e(route('home')); ?>#event">Event</a></li>
                <li><a href="<?php echo e(route('home')); ?>#tickets">Tickets</a></li>
                <li><a href="<?php echo e(route('home')); ?>#contact">Contact</a></li>
            </ul>

            <div class="footer-right">
                <p>© Sneakerness®</p>
            </div>

        </div>
    </footer>

    <script>
        // Open en sluit het mobiele menu
        function toggleMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }
    </script>

</body>

</html><?php /**PATH C:\Sneakerness--event\Sneakerness\resources\views/contactpersonen/index.blade.php ENDPATH**/ ?>