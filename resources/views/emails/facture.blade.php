<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Facture Interco</title>
</head>
<body>
    <p>Bonjour <strong>{{ $name }}</strong>,</p>

    <p>
        Veuillez trouver en pièce jointe votre facture Interco
        pour la période <strong>{{ substr($periode, 5, 2) }}/{{ substr($periode, 0, 4) }}</strong>.
    </p>

    <p>Cordialement, <br>
       <strong>L’équipe Interco TogoCom</strong></p>
</body>
</html>
