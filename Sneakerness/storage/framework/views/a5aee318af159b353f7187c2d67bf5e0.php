<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inloggen | Sneakerness®</title>

    <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
</head>

<body>

    <div class="login-pagina">

        <!-- Linkerkant -->
        <section class="login-afbeelding">
            <div class="logo">
                SNEAKERNESS<span>®</span>
            </div>

            <div class="event-info">
                <h2>ROTTERDAM<br>2025</h2>
                <p>Van Nellefabriek · 14–15 Juni</p>
            </div>
        </section>

        <!-- Rechterkant -->
        <section class="login-gedeelte">
            <div class="login-container">

                <h1>INLOGGEN</h1>

                <p class="ondertitel">
                    Log in met je Sneakerness®-account
                </p>

                <!-- Foutmelding -->
                <?php if($errors->any()): ?>
                    <div class="foutmelding">
                        E-mailadres of wachtwoord is onjuist. Probeer het opnieuw.
                    </div>
                <?php endif; ?>

                <!-- Loginformulier -->
                <form method="POST" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="veld">
                        <label for="email">E-mailadres</label>

                        <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>"
                            placeholder="naam@sneakerness.nl" required autofocus autocomplete="username">
                    </div>

                    <div class="veld">
                        <label for="password">Wachtwoord</label>

                        <input id="password" type="password" name="password" required autocomplete="current-password">
                    </div>

                    <?php if(Route::has('password.request')): ?>
                        <a href="<?php echo e(route('password.request')); ?>" class="wachtwoord-vergeten">
                            Wachtwoord vergeten?
                        </a>
                    <?php endif; ?>

                    <button type="submit" class="inloggen-knop">
                        Inloggen
                    </button>
                </form>
            </div>
        </section>

    </div>

</body>

</html>
<?php /**PATH C:\Sneakerness--event\Sneakerness\resources\views/auth/login.blade.php ENDPATH**/ ?>