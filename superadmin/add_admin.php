<?php
session_start();
if (!isset($_SESSION['superAdmin'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../classes/Party.php';

$party = new Party();
$parties = $party->getAll();
?>

<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin toevoegen</title>
    <link rel="stylesheet" href="../styles/algemeen.css">
    <link rel="stylesheet" href="../styles/add_page.css">
</head>
<body>
<header>
    <div class="logo-container">
        <h1 class="stemwijzer-logo_text">
            <span>Politieke</span> <span>Stemwijzer</span>
        </h1>
    </div>
    <a class="logout-button" href="admins.php">Terug</a>
</header>
<hr>

<div class="container">
    <h2 class="title">Admin toevoegen</h2>
    <div class="form-container">
        <form method="POST">
            <label for="email">E-mailadres:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Wachtwoord:</label>
            <input type="password" name="password" id="password" required>

            <select name="party" id="party" required>
                <option value="">Selecteer een partij</option>
                <?php
                 foreach($parties as $party) {
                     echo "<option value='" . $party['partyID'] . "'>" . $party['name'] . "</option>";
                 }
                ?>
            </select>

            <button type="submit">Admin toevoegen</button>
            <p class="errorMessage"></p>
        </form>
    </div>
</div>

<script src="../js/api.js"></script>
<script>
    const form = document.querySelector('form');
    const errorMessage = document.querySelector('.errorMessage');

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const party = document.querySelector('#party').value;
        const email = document.querySelector('#email').value;
        const password = document.querySelector('#password').value;

        if (email === '') {
            errorMessage.textContent = 'Vul een e-mailadres in.';
            return;
        }
        if (password === '') {
            errorMessage.textContent = 'Vul een wachtwoord in.';
            return;
        }
        if (party === '') {
            errorMessage.textContent = 'Selecteer een partij.';
            return;
        }

        addAdmin(party, email, password)
            .then(response => {
                errorMessage.textContent = 'Admin succesvol toegevoegd';
                setTimeout(() => window.location.href = 'admins.php', 1000);
            })
            .catch(error => {
                errorMessage.textContent = error.message;
            });
    });
</script>

</body>
</html>