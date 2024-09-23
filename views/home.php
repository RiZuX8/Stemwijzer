<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-
scale=1.0">
    <title>Home</title>
</head>

<body>
    <h1>Welkom op de homepage</h1>
    <p>Dit is de startpagina van de website.</p>
    <form action="/simple_router/contact" method="POST">
        <label for="name">Naam:</label>
        <input type="text" id="name" name="name">
        <button type="submit">Verstuur</button>
    </form>
    if ($uri == 'contact' && $method == 'POST') {
    echo 'Bedankt voor het indienen, ' . $_POST['name'];
    }
</body>

</html>