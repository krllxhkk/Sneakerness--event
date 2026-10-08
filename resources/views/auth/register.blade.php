<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registreren | Sneakerness®</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>
    <main class="login-pagina">

        <!-- Linkerkant: afbeelding en evenementinformatie -->
        <section class="login-afbeelding">
            <a href="{{ route('home') }}" class="logo">
                SNEAKERNESS<span>®</span>
            </a>

            <div class="event-info">
                <h2>ROTTERDAM<br>2025</h2>
                <p>Van Nellefabriek · 14–15 Juni</p>
            </div>
        </section>

        <!-- Rechterkant: registratieformulier -->
        <section class="login-gedeelte">
            <div class="login-container">

                <h1>REGISTREREN</h1>
                <p class="ondertitel">
                    Maak je Sneakerness®-account aan
                </p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Naam -->
                    <div class="veld">
                        <label for="name">Naam</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                            placeholder="Vul je naam in" autocomplete="name" maxlength="255" required autofocus>

                        @error('name')
                            <p class="registratie-fout">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- E-mailadres -->
                    <div class="veld">
                        <label for="email">E-mailadres</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            placeholder="Vul je e-mailadres in" autocomplete="email" maxlength="255" required>

                        @error('email')
                            <p class="registratie-fout">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Wachtwoord -->
                    <div class="veld">
                        <label for="password">Wachtwoord</label>
                        <input id="password" type="password" name="password" placeholder="Maak een wachtwoord"
                            autocomplete="new-password" required>

                        @error('password')
                            <p class="registratie-fout">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Wachtwoord bevestigen -->
                    <div class="veld">
                        <label for="password_confirmation">
                            Wachtwoord bevestigen
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            placeholder="Herhaal je wachtwoord" autocomplete="new-password" required>

                        @error('password_confirmation')
                            <p class="registratie-fout">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inloggen-knop">
                        REGISTREREN
                    </button>
                </form>

                <p class="registratie-login-link">
                    Heb je al een account?
                    <a href="{{ route('login') }}">Inloggen</a>
                </p>

            </div>
        </section>

    </main>
</body>

</html>
