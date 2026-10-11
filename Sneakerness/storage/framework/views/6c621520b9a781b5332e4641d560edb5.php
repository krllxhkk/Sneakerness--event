<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Stands | Sneakerness®</title>

    <!-- Algemene CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">

    <!-- Stands CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/stands.css')); ?>">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


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


        
        <?php if($stands->count() > 0): ?>

            <section class="stands-grid">

                <?php $__currentLoopData = $stands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <article class="stand-card">

                        <!-- Bovenkant van de kaart -->
                        <div class="stand-card-top">

                            <span class="stand-type">
                                STAND <?php echo e($stand->StandType); ?>

                            </span>


                            <?php if($stand->VerhuurdStatus): ?>

                                <span class="status rented">
                                    VERHUURD
                                </span>

                            <?php else: ?>

                                <span class="status available">
                                    BESCHIKBAAR
                                </span>

                            <?php endif; ?>

                        </div>


                        <!-- Naam -->
                        <h2>

                            <?php if($stand->VerhuurdStatus && $stand->verkoper): ?>

                                <?php echo e($stand->verkoper->Naam); ?>


                            <?php else: ?>

                                VRIJE STAND

                            <?php endif; ?>

                        </h2>


                        <!-- Informatie -->
                        <div class="stand-details">


                            <!-- Status -->
                            <div class="stand-detail">

                                <span>
                                    STATUS
                                </span>

                                <strong>
                                    <?php if($stand->VerhuurdStatus): ?>
                                        Verhuurd
                                    <?php else: ?>
                                        Beschikbaar
                                    <?php endif; ?>
                                </strong>

                            </div>


                            <!-- Standtype -->
                            <div class="stand-detail">

                                <span>
                                    STANDTYPE
                                </span>

                                <strong>
                                    <?php echo e($stand->StandType); ?>

                                </strong>

                            </div>


                            <!-- Prijs -->
                            <div class="stand-detail">

                                <span>
                                    PRIJS
                                </span>

                                <strong>
                                    €<?php echo e(number_format($stand->Prijs, 2, ',', '.')); ?>

                                </strong>

                            </div>


                            <!-- Verkoper -->
                            <div class="stand-detail">

                                <span>
                                    VERKOPER
                                </span>

                                <strong>

                                    <?php if($stand->VerhuurdStatus && $stand->verkoper): ?>

                                        <?php echo e($stand->verkoper->Naam); ?>


                                    <?php else: ?>

                                        Nog niet verhuurd

                                    <?php endif; ?>

                                </strong>

                            </div>


                            <!-- Categorie / soort -->
                            <div class="stand-detail">

                                <span>
                                    CATEGORIE
                                </span>

                                <strong>

                                    <?php if($stand->VerhuurdStatus && $stand->verkoper): ?>

                                        <?php echo e($stand->verkoper->VerkooptSoort ?? '-'); ?>


                                    <?php else: ?>

                                        -

                                    <?php endif; ?>

                                </strong>

                            </div>

                        </div>

                    </article>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </section>


        
        <?php else: ?>

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

        <?php endif; ?>

    </main>


    <!-- Footer -->
    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</body>

</html><?php /**PATH C:\Sneakerness--event\Sneakerness\resources\views/stands/index.blade.php ENDPATH**/ ?>