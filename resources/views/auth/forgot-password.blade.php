<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Wachtwoord vergeten | Sneakerness®</title>

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

                <h1>WACHTWOORD VERGETEN?</h1>

                <p class="ondertitel">
                    Vul je e-mailadres in om een link voor een nieuw wachtwoord te ontvangen.
                </p>

                <!-- Succesmelding -->
                @if (session('status'))
                    <div class="success-message">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Foutmelding -->
                @if ($errors->any())
                    <div class="foutmelding">
                        Controleer het e-mailadres en probeer het opnieuw.
                    </div>
                @endif

                <!-- Resetformulier -->
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="veld">
                        <label for="email">E-mailadres</label>

                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            placeholder="naam@sneakerness.nl" required autofocus autocomplete="email">
                    </div>

                    <button type="submit" class="inloggen-knop">
                        Resetlink versturen
                    </button>
                </form>

                <a href="{{ route('login') }}" class="wachtwoord-vergeten">
                    ← Terug naar inloggen
                </a>

            </div>
        </section>

    </div>

</body>

</html>
