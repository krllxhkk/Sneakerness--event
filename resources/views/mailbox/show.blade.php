<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mijn ticketbericht | Sneakerness®</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mailbox.css') }}">
</head>

<body>

    <main class="bericht-pagina">
        <div class="bericht-container">

            <a href="{{ route('tickets.bevestiging') }}" class="bericht-terug">
                ← TERUG NAAR BEVESTIGING
            </a>

            <h1 class="bericht-titel">
                MIJN TICKETBERICHT
            </h1>

            <article class="bericht-kaart">

                <div class="bericht-header">
                    <h2>SNEAKERNESS<sup>®</sup></h2>
                    <p>ROTTERDAM 2026</p>
                </div>

                <div class="bericht-inhoud">

                    <h3 class="bericht-onderwerp">
                        {{ $bericht['onderwerp'] }}
                    </h3>

                    <div class="bericht-meta">
                        <div>Van: Sneakerness®</div>
                        <div>
                            Aan: {{ $bericht['bestelling']['email'] }}
                        </div>
                        <div>
                            Ontvangen: {{ $bericht['ontvangen_op'] }}
                        </div>
                    </div>

                    <p class="bericht-tekst">
                        Beste {{ $bericht['bestelling']['naam'] }},
                        <br>
                        Bedankt voor je bestelling bij Sneakerness®!
                        Je tickets zijn succesvol besteld.
                        Hieronder vind je alle gegevens van je bestelling.
                    </p>

                    <div class="bericht-gegevens">

                        <div class="bericht-rij">
                            <span>Bestelnummer</span>
                            <strong>
                                #{{ $bericht['bestelling']['id'] }}
                            </strong>
                        </div>

                        <div class="bericht-rij">
                            <span>Naam</span>
                            <strong>
                                {{ $bericht['bestelling']['naam'] }}
                            </strong>
                        </div>

                        <div class="bericht-rij">
                            <span>E-mailadres</span>
                            <strong>
                                {{ $bericht['bestelling']['email'] }}
                            </strong>
                        </div>

                        <div class="bericht-rij">
                            <span>Dag</span>
                            <strong>
                                {{ $bericht['bestelling']['dag'] }}
                            </strong>
                        </div>

                        <div class="bericht-rij">
                            <span>Datum</span>
                            <strong>
                                {{ $bericht['bestelling']['datum'] }}
                            </strong>
                        </div>

                        <div class="bericht-rij">
                            <span>Tijdslot</span>
                            <strong>
                                {{ $bericht['bestelling']['tijdslot'] }}
                            </strong>
                        </div>

                        <div class="bericht-rij">
                            <span>Aantal tickets</span>
                            <strong>
                                {{ $bericht['bestelling']['aantal'] }}
                            </strong>
                        </div>

                        <div class="bericht-rij">
                            <span>Betaalmethode</span>
                            <strong>
                                {{ $bericht['bestelling']['betaalmethode'] === 'ideal' ? 'iDEAL' : 'Contant' }}
                            </strong>
                        </div>

                        <div class="bericht-rij">
                            <span>Totaalprijs</span>
                            <strong class="geel">
                                €{{ number_format($bericht['bestelling']['totaalprijs'], 2, ',', '.') }}
                            </strong>
                        </div>

                    </div>

                    <h3 class="bericht-subtitel">
                        JOUW TICKETNUMMERS
                    </h3>

                    @foreach ($bericht['bestelling']['ticketnummers'] as $ticketnummer)
                        <div class="bericht-ticketnummer">
                            🎟 {{ $ticketnummer }}
                        </div>
                    @endforeach

                    <p class="bericht-tekst bericht-afsluiting">
                        Bewaar je ticketgegevens goed.
                        We wensen je veel plezier bij Sneakerness®!
                    </p>

                </div>
            </article>

        </div>
    </main>

</body>

</html>
