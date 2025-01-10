<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../classes/Party.php';

$party = new Party();
$partyID = $_SESSION['admin']['partyID'] ?? null;

if (!$partyID) {
    header('Location: home.php');
    exit();
}

$partyData = $party->getById($partyID);
if (!$partyData) {
    header('Location: home.php');
    exit();
}

$imageName = basename(parse_url($partyData['image'], PHP_URL_PATH));

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
        <img src="../assets/vote.svg" alt="participate checklist" />
    </div>
    <a class="logout-button" href="../login.php?logout">Uitloggen</a>
</header>

<div class="greeting-container">
    <div class="party-image">
        <img src="<?php echo $partyData['image']; ?>" alt="<?php echo $partyData['name']; ?> logo">
    </div>
    <div class="greeting-text">
        <h2>Welkom bij uw partij!</h2>
        <h3><?php echo $partyData['name']; ?></h3>
    </div>
</div>
<div class="container">
    <a href="partij.php" class="button">Overzicht</a>
    <a href="stellingen.php" class="button">Stellingen</a>
</div>
</body>
</html>