<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Events | Sneakerness®</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/events.css')); ?>">

    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
<?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <main class="events-container">

        <section class="events-header">
            <span class="events-label">SNEAKERNESS® EVENTS</span>

            <h1>EVENTS<br>OVERZICHT</h1>

            <p>
                Bekijk alle aankomende Sneakerness® evenementen.
                Hier vind je de datum, locatie, beschikbare tickets
                en stands per evenement.
            </p>
        </section>


        
        <?php if($events->count() > 0): ?>

            <section class="events-grid">

                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <article class="event-card">

                        <div class="event-card-top">
                            <span class="event-date">
                                <?php echo e(\Carbon\Carbon::parse($event->Datum)->format('d-m-Y')); ?>

                            </span>

                            <span class="event-status">
                                EVENT
                            </span>
                        </div>

                        <h2><?php echo e($event->Naam); ?></h2>

                        <div class="event-details">

                            <div class="event-detail">
                                <span class="detail-label">LOCATIE</span>
                                <strong><?php echo e($event->Locatie); ?></strong>
                            </div>

                            <div class="event-detail">
                                <span class="detail-label">TICKETS PER TIJDSLOT</span>
                                <strong><?php echo e($event->AantalTicketsPerTijdslot); ?></strong>
                            </div>

                            <div class="event-detail">
                                <span class="detail-label">BESCHIKBARE STANDS</span>
                                <strong><?php echo e($event->BeschikbareStands); ?></strong>
                            </div>

                        </div>

                    </article>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </section>


            
        <?php else: ?>

            <section class="no-events">

                <div class="no-events-icon">!</div>

                <h2>GEEN EVENTS BESCHIKBAAR</h2>

                <p>
                    Er zijn momenteel geen evenementen beschikbaar.
                    Bekijk deze pagina later opnieuw.
                </p>

            </section>

        <?php endif; ?>

    </main>

</body>
<?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</html><?php /**PATH C:\Sneakerness--event\Sneakerness\resources\views/events/index.blade.php ENDPATH**/ ?>