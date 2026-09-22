<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Verkopers</title>
</head>
<body>

    <h1>Verkopers</h1>

    @foreach($verkopers as $verkoper)
        <div>
            <h2>{{ $verkoper->Naam }}</h2>

            <p>Soort: {{ $verkoper->VerkooptSoort }}</p>
            <p>Stand type: {{ $verkoper->StandType }}</p>
            <p>Dagen: {{ $verkoper->Dagen }}</p>
        </div>

        <hr>
    @endforeach

</body>
</html>