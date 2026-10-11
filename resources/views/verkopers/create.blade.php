<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verkoper toevoegen | Sneakerness®</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<header class="dashboard-header">

    <div class="dashboard-logo">
        SNEAKERNESS<span>®</span>
    </div>

    <nav class="dashboard-nav">
        <a href="{{ route('organisator.dashboard') }}">Dashboard</a>
        <a href="{{ route('events.index') }}">Evenementen</a>
        <a href="#">Tickets</a>
        <a href="#">Bezoekers</a>
        <a href="{{ route('organisator.verkopers') }}" class="actief">Verkopers</a>
        <a href="{{ route('stands.index') }}">Stands</a>
        <a href="#">Prijzen</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" class="uitloggen-formulier">
        @csrf

        <button type="submit" class="uitloggen-knop">
            UITLOGGEN
        </button>
    </form>

</header>


<div class="dashboard-layout">

    <aside class="zijbalk">

        <p class="zijbalk-label">
            Organisator
        </p>

        <h2>
            Beheer Panel
        </h2>

        <nav class="beheer-menu">

            <a href="{{ route('organisator.dashboard') }}">
                Dashboard
            </a>

            <a href="{{ route('events.index') }}">
                Evenementen
            </a>

            <a href="#">
                Tickets
            </a>

            <a href="#">
                Bezoekers
            </a>

            <a href="{{ route('organisator.verkopers') }}" class="actief">
                Verkopers
            </a>

            <a href="{{ route('stands.index') }}">
                Stands
            </a>

            <a href="#">
                Prijzen
            </a>

        </nav>

    </aside>


    <main class="dashboard-inhoud">

        <div class="dashboard-kop">

            <p>
                Organisator Dashboard
            </p>

            <h1>
                Verkoper toevoegen
            </h1>

            <span>
                Voeg een nieuwe verkoper toe aan Sneakerness®.
            </span>

        </div>


        <section class="toevoegen-formulier">

            {{-- Succesmelding --}}
            @if (session('success'))
                <div class="melding melding-succes">
                    {{ session('success') }}
                </div>
            @endif


            {{-- Foutmelding --}}
            @if (session('error'))
                <div class="melding melding-fout">
                    {{ session('error') }}
                </div>
            @endif


            {{-- Validatiefouten --}}
            @if ($errors->any())
                <div class="melding melding-fout">

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif


            <form method="POST" action="{{ route('verkopers.store') }}">

                @csrf


                <div class="form-groep">

                    <label for="Naam">
                        Naam *
                    </label>

                    <input
                        type="text"
                        id="Naam"
                        name="Naam"
                        value="{{ old('Naam') }}"
                        maxlength="100"
                        required
                    >

                </div>


                <div class="form-groep">

                    <label for="VerkooptSoort">
                        Verkoopt soort *
                    </label>

                    <input
                        type="text"
                        id="VerkooptSoort"
                        name="VerkooptSoort"
                        value="{{ old('VerkooptSoort') }}"
                        maxlength="100"
                        placeholder="Bijvoorbeeld: Sneakers"
                        required
                    >

                </div>


                <div class="form-groep">

                    <label for="StandType">
                        Stand type *
                    </label>

                    <select
                        id="StandType"
                        name="StandType"
                        required
                    >

                        <option value="">
                            Kies een stand type
                        </option>

                        <option
                            value="A"
                            {{ old('StandType') == 'A' ? 'selected' : '' }}
                        >
                            A
                        </option>

                        <option
                            value="AA"
                            {{ old('StandType') == 'AA' ? 'selected' : '' }}
                        >
                            AA
                        </option>

                        <option
                            value="AA+"
                            {{ old('StandType') == 'AA+' ? 'selected' : '' }}
                        >
                            AA+
                        </option>

                    </select>

                </div>


                <div class="form-groep">

                    <label for="Dagen">
                        Dagen *
                    </label>

                    <input
                        type="text"
                        id="Dagen"
                        name="Dagen"
                        value="{{ old('Dagen') }}"
                        maxlength="50"
                        placeholder="Bijvoorbeeld: Zaterdag"
                        required
                    >

                </div>


                <div class="form-groep">

                    <label for="SpecialeStatus">
                        Speciale status
                    </label>

                    <select
                        id="SpecialeStatus"
                        name="SpecialeStatus"
                    >

                        <option
                            value=""
                            {{ old('SpecialeStatus') == '' ? 'selected' : '' }}
                        >
                            Geen
                        </option>

                        <option
                            value="Partner"
                            {{ old('SpecialeStatus') == 'Partner' ? 'selected' : '' }}
                        >
                            Partner
                        </option>

                        <option
                            value="Premium"
                            {{ old('SpecialeStatus') == 'Premium' ? 'selected' : '' }}
                        >
                            Premium
                        </option>

                    </select>

                </div>


                <div class="form-groep">

                    <label for="Opmerking">
                        Opmerking
                    </label>

                    <textarea
                        id="Opmerking"
                        name="Opmerking"
                        rows="4"
                        maxlength="250"
                        placeholder="Optionele opmerking"
                    >{{ old('Opmerking') }}</textarea>

                </div>


                <div class="formulier-knoppen">

                    <a
                        href="{{ route('organisator.verkopers') }}"
                        class="annuleer-knop"
                    >
                        ANNULEREN
                    </a>

                    <button
                        type="submit"
                        class="opslaan-knop"
                    >
                        VERKOPER TOEVOEGEN
                    </button>

                </div>

            </form>

        </section>

    </main>

</div>

</body>
</html>