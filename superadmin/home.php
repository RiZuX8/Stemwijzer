<?php
session_start();
if (!isset($_SESSION['superAdmin'])) {
    header('Location: ../login.php');
    exit();
}
?>

<!doctype html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link rel="stylesheet" href="../styles/admin_home.css">
</head>

<body>
    <header>
        <div class="logo-container">
            <h1 class="stemwijzer-logo_text">
                <span>Politieke</span> <span>Stemwijzer</span>
            </h1>
        </div>
        <a class="logout-button" href="../login.php?logout">Uitloggen</a>
    </header>


    <div class="greeting-container">

        <div class="greeting-text">
            <h2>Welkom,<br></h2>
            <h3>Superadmin-san</h3>

        </div>
    </div>

    <div class="container">
        <a href="admins.php" class="button">Admins</a>
        <a href="partijen.php" class="button">Partij</a>
        <a href="statements.php" class="button">Stellingen</a>
    </div>
</body>

</html>