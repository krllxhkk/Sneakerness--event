<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inloggen | Sneakerness®</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
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
                @if ($errors->any())
                    <div class="foutmelding">
                        E-mailadres of wachtwoord is onjuist. Probeer het opnieuw.
                    </div>
                @endif

                <!-- Loginformulier -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="veld">
                        <label for="email">E-mailadres</label>

                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            placeholder="naam@sneakerness.nl" required autofocus autocomplete="username">
                    </div>

                    <div class="veld">
                        <label for="password">Wachtwoord</label>

                        <input id="password" type="password" name="password" required autocomplete="current-password">
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="wachtwoord-vergeten">
                            Wachtwoord vergeten?
                        </a>
                    @endif

                    <button type="submit" class="inloggen-knop">
                        Inloggen
                    </button>
                    <p class="registratie-link">
                        Nog geen account?
                        <a href="{{ route('register') }}">Registreren</a>
                    </p>
                </form>
            </div>
        </section>

    </div>

</body>

</html>
