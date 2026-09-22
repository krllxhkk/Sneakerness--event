<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Verkopers</title>
</head>
<body>

    <h1>Verkopers</h1>

    {{-- Toon alle actieve verkopers --}}
    @foreach($verkopers as $verkoper)
        <div>
            {{-- Toon de naam van de verkoper --}}
            <h2>{{ $verkoper->Naam }}</h2>

            {{-- Toon de gegevens van de verkoper --}}
            <p>Speciale status: {{ $verkoper->SpecialeStatus ?? 'Geen' }}</p>
            <p>Verkoopt: {{ $verkoper->VerkooptSoort }}</p>
            <p>Stand type: {{ $verkoper->StandType }}</p>
            <p>Dagen: {{ $verkoper->Dagen }}</p>

            {{-- Toon het logo als het bestaat --}}
            @if($verkoper->Logo)
                <p>Logo: {{ $verkoper->Logo }}</p>
            @endif

            {{-- Toon de opmerking als deze bestaat --}}
            @if($verkoper->Opmerking)
                <p>Opmerking: {{ $verkoper->Opmerking }}</p>
            @endif
        </div>

        <hr>
    @endforeach

</body>
</html>