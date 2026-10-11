<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets Kopen | Sneakerness® Rotterdam 2026</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Inter:wght@400;600;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/tickets.css')); ?>">
</head>

<body>

    <header>
        <div class="header-container">
            <!-- Logo -->
            <a href="<?php echo e(route('home')); ?>" class="logo">SNEAKERNESS<sup>®</sup></a>

            <!-- Hamburger Menu Icoon -->
            <div class="hamburger" id="hamburgerBtn" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <!-- Navigatielinks -->
            <ul class="nav-links" id="navLinks">
                <li><a href="<?php echo e(route('home')); ?>" class="<?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">Home</a></li>
                <li><a href="<?php echo e(route('events.index')); ?>"
                        class="<?php echo e(request()->routeIs('events.index') ? 'active' : ''); ?>">Event</a></li>
                <li><a href="<?php echo e(route('tickets.index')); ?>"
                        class="<?php echo e(request()->routeIs('tickets.index') ? 'active' : ''); ?>">Tickets</a></li>
                <li><a href="<?php echo e(route('verkopers.index')); ?>"
                        class="<?php echo e(request()->routeIs('verkopers.index') ? 'active' : ''); ?>">Verkopers</a></li>
                <li><a href="<?php echo e(route('stands.index')); ?>"
                        class="<?php echo e(request()->routeIs('stands.index') ? 'active' : ''); ?>">Stands</a></li>
            </ul>

            <!-- Rechterkant van de navigatie -->
            <div class="nav-right">
                <a href="#" class="login-link">LOGIN</a>
                <a href="<?php echo e(route('tickets.index')); ?>" class="btn-yellow-sm">TICKET KOPEN</a>
            </div>
        </div>
    </header>
    <main class="main-container">

        <?php if(!empty($errorMessage)): ?>
            <div class="error-alert"><?php echo e($errorMessage); ?></div>
        <?php endif; ?>

        <div class="page-header">
            <span class="page-subtitle">SNEAKERNESS® ROTTERDAM 2026</span>
            <h1 class="page-title">TICKETS KOPEN</h1>
        </div>

        <!-- STEPPER -->
        <div class="stepper">
            <div class="step-item">
                <div class="step-circle <?php echo e($stap === 1 ? 'active' : 'completed'); ?>">
                    <?php echo $stap > 1 ? '&#10003;' : '1'; ?>

                </div>
                <span class="step-label <?php echo e($stap === 1 ? 'active' : ''); ?>">Dag</span>
            </div>
            <div class="step-item">
                <div class="step-circle <?php echo e($stap === 2 ? 'active' : ''); ?>">2</div>
                <span class="step-label <?php echo e($stap === 2 ? 'active' : ''); ?>">Tijdslot</span>
            </div>
            <div class="step-item">
                <div class="step-circle">3</div>
                <span class="step-label">Gegevens</span>
            </div>
            <div class="step-item">
                <div class="step-circle">4</div>
                <span class="step-label">Overzicht</span>
            </div>
        </div>

        <!-- STAP 1: KIES JE DAG -->
        <?php if($stap === 1): ?>
            <h2 class="section-title">KIES JE DAG</h2>

            <div class="day-grid">
                <!-- Zaterdag Kaart -->
                <a href="<?php echo e(route('tickets.index', ['dag' => 'zaterdag', 'stap' => 1])); ?>"
                    class="day-card <?php echo e($gekozenDag === 'zaterdag' ? 'selected' : ''); ?>">
                    <div class="icon">📅</div>
                    <h3>ZATERDAG</h3>
                    <p>14 november 2026</p>
                    <p class="location">Sneakerness® Rotterdam</p>
                    <?php if($gekozenDag === 'zaterdag'): ?>
                        <div class="check-badge">&#10003;</div>
                    <?php endif; ?>
                </a>

                <!-- Zondag Kaart -->
                <a href="<?php echo e(route('tickets.index', ['dag' => 'zondag', 'stap' => 1])); ?>"
                    class="day-card <?php echo e($gekozenDag === 'zondag' ? 'selected' : ''); ?>">
                    <div class="icon">☀️</div>
                    <h3>ZONDAG</h3>
                    <p>15 november 2026</p>
                    <p class="location">Sneakerness® Rotterdam</p>
                    <?php if($gekozenDag === 'zondag'): ?>
                        <div class="check-badge">&#10003;</div>
                    <?php endif; ?>
                </a>
            </div>

            <?php if(!empty($gekozenDag)): ?>
                <div class="actions">
                    <a href="<?php echo e(route('tickets.index', ['dag' => $gekozenDag, 'stap' => 2])); ?>"
                        class="btn-volgende">VOLGENDE STAP &rarr;</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- STAP 2: KIES JE TIJDSLOT -->
        <?php if($stap === 2): ?>
            <h2 class="section-title">KIES JE TIJDSLOT</h2>
            <p class="step-subtitle">
                <?php echo e($gekozenDag === 'zaterdag' ? 'Zaterdag · 14 November 2026' : 'Zondag · 15 November 2026'); ?>

            </p>

            <div class="timeslot-list">
                <?php
                    $huidigeTickets = $gekozenDag === 'zaterdag' ? $ticketsZaterdag : $ticketsZondag;
                ?>

                <?php $__empty_1 = true; $__currentLoopData = $huidigeTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="timeslot-card">
                        <div class="timeslot-left">
                            <div class="timeslot-time">
                                <?php echo e(substr($ticket->tijdslot, 0, 5)); ?>

                            </div>
                            <div class="timeslot-info">
                                <span class="timeslot-label">INGANG</span>
                                <span class="timeslot-available">
                                    Nog <?php echo e($ticket->aantal_tickets_per_tijdslot); ?> tickets beschikbaar
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="timeslot-price">
                                €<?php echo e(number_format($ticket->tarief, 0)); ?>

                            </div>
                            <div class="timeslot-unit">p.p.</div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php if(empty($errorMessage)): ?>
                        <p class="empty-message">Geen beschikbare tickets voor deze dag.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="actions has-back">
                <a href="<?php echo e(route('tickets.index', ['dag' => $gekozenDag, 'stap' => 1])); ?>" class="btn-terug">&larr;
                    TERUG</a>
                <button class="btn-volgende">VOLGENDE STAP &rarr;</button>
            </div>
        <?php endif; ?>

    </main>

    <!-- JAVASCRIPT VOOR HAMBURGER MENU -->
    <script>
        function toggleMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }
    </script>

</body>

</html>
<?php /**PATH C:\Sneakerness--event\Sneakerness\resources\views/tickets/index.blade.php ENDPATH**/ ?>