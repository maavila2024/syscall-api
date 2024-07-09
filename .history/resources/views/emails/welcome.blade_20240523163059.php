<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Registro</title>
</head>
<body>
    <h1>Confirmação de Registro</h1>

    <p>Obrigado por se registrar! Por favor, clique no link abaixo para confirmar seu registro:</p>

    @component('mail::button', ['url' => $confirmationUrl])
        Confirmar Registro
    @endcomponent

    <p>Se você não se registrou em nosso site, ignore este email.</p>

    <p>Obrigado,<br>
    Sua Equipe</p>
</body>
</html>
