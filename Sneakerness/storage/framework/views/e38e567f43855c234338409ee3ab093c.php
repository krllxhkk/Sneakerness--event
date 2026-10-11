<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Organisator Dashboard | Sneakerness®</title>

    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
</head>

<body>

    <header class="dashboard-header">

        <div class="dashboard-logo">
            SNEAKERNESS<span>®</span>
        </div>

        <nav class="dashboard-nav">
            <a href="<?php echo e(route('organisator.dashboard')); ?>" class="actief">
                Dashboard
            </a>

            <a href="<?php echo e(route('events.index')); ?>">
                Evenementen
            </a>

            <a href="#">
                Tickets
            </a>

            <a href="#">
                Bezoekers
            </a>

            <a href="<?php echo e(route('verkopers.index')); ?>">
                Verkopers
            </a>

            <a href="<?php echo e(route('stands.index')); ?>">
                Stands
            </a>

            <a href="#">
                Prijzen
            </a>
        </nav>

        <form method="POST" action="<?php echo e(route('logout')); ?>" class="uitloggen-formulier">
            <?php echo csrf_field(); ?>

            <button type="submit" class="uitloggen-knop">
                UITLOGGEN
            </button>
        </form>

    </header>


    <div class="dashboard-layout">

        <!-- Beheer Panel -->
        <aside class="zijbalk">

            <p class="zijbalk-label">
                Organisator
            </p>

            <h2>Beheer Panel</h2>

            <nav class="beheer-menu">

                <a href="<?php echo e(route('organisator.dashboard')); ?>" class="actief">
                    Dashboard
                </a>

                <a href="<?php echo e(route('events.index')); ?>">
                    Evenementen
                </a>

                <a href="#">
                    Tickets
                </a>

                <a href="#">
                    Bezoekers
                </a>

                <a href="<?php echo e(route('verkopers.index')); ?>">
                    Verkopers
                </a>

                <a href="<?php echo e(route('stands.index')); ?>">
                    Stands
                </a>

                <a href="#">
                    Prijzen
                </a>

            </nav>

        </aside>


        <!-- Dashboard inhoud -->
        <main class="dashboard-inhoud">

            <div class="dashboard-kop">

                <p>Organisator Dashboard</p>

                <h1>Welkom bij Sneakerness®</h1>

                <span>
                    Beheer het evenement vanuit het Beheer Panel.
                </span>

            </div>


            <!-- Statistieken -->
            <section class="statistieken">

                <div class="statistiek-kaart">
                    <p>Totaal tickets</p>
                    <h3><?php echo e($totaalTickets); ?></h3>
                    <span class="kaart-icoon">🎟️</span>
                </div>

                <div class="statistiek-kaart">
                    <p>Verkochte tickets</p>
                    <h3><?php echo e($verkochteTickets); ?></h3>
                    <span class="kaart-icoon">✓</span>
                </div>

                <div class="statistiek-kaart">
                    <p>Beschikbare tickets</p>
                    <h3><?php echo e($beschikbareTickets); ?></h3>
                    <span class="kaart-icoon">🎫</span>
                </div>

                <div class="statistiek-kaart">
                    <p>Totale omzet</p>
                    <h3>€<?php echo e(number_format($totaleOmzet, 2, ',', '.')); ?></h3>
                    <span class="kaart-icoon">💰</span>
                </div>

                <div class="statistiek-kaart">
                    <p>Totaal stands</p>
                    <h3><?php echo e($totaalStands); ?></h3>
                    <span class="kaart-icoon">🏬</span>
                </div>

                <div class="statistiek-kaart">
                    <p>Verhuurde stands</p>
                    <h3><?php echo e($verhuurdeStands); ?></h3>
                    <span class="kaart-icoon">✓</span>
                </div>

                <div class="statistiek-kaart">
                    <p>Beschikbare stands</p>
                    <h3><?php echo e($beschikbareStands); ?></h3>
                    <span class="kaart-icoon">◻</span>
                </div>

                <div class="statistiek-kaart">
                    <p>Bezoekers</p>
                    <h3><?php echo e($aantalBezoekers); ?></h3>
                    <span class="kaart-icoon">👥</span>
                </div>

            </section>

            <section class="dashboard-blokken">

                <div class="dashboard-blok tickets-tijdsloten">
                    <h2>Tickets per tijdslot</h2>

                    <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="tijdslot-rij">

                            <span class="tijdslot-naam">
                                <?php echo e(\Carbon\Carbon::parse($ticket->datum)->format('d-m')); ?>

                                <?php echo e(\Carbon\Carbon::parse($ticket->tijdslot)->format('H:i')); ?>

                            </span>

                            <div class="tijdslot-balk-achtergrond">
                                <div class="tijdslot-balk"
                                    style="width: <?php echo e(($ticket->aantal_tickets_per_tijdslot / 410) * 100); ?>%;">
                                </div>
                            </div>

                            <span class="tijdslot-aantal">
                                <?php echo e($ticket->aantal_tickets_per_tijdslot); ?>

                            </span>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

                <div class="dashboard-blok">
                    <h2>Verhuurde stands per type</h2>

                    <p class="geen-gegevens">
                        Er zijn nog geen standgegevens beschikbaar.
                    </p>
                </div>

            </section>

            <!-- Recente ticketbestellingen -->
            <section class="recente-sectie">

                <div class="sectie-kop">
                    <h2>Recente ticketbestellingen</h2>

                    <a href="#">
                        Alle tickets →
                    </a>
                </div>

                <div class="dashboard-tabel">
                    <table>
                        <thead>
                            <tr>
                                <th>Naam</th>
                                <th>E-mail</th>
                                <th>Dag</th>
                                <th>Tijdslot</th>
                                <th>Aantal</th>
                                <th>Prijs</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td colspan="7" class="lege-tabel">
                                    Er zijn nog geen ticketbestellingen.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </section>

        </main>

    </div>

</body>

</html>
<?php /**PATH C:\Sneakerness--event\Sneakerness\resources\views/organisator/dashboard.blade.php ENDPATH**/ ?>