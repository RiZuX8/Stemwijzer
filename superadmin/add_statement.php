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
    <title>Stelling toevoegen</title>
    <link rel="stylesheet" href="../styles/algemeen.css">
    <link rel="stylesheet" href="../styles/stelling-maken.css">
</head>

<body>
    <header>
        <div class="logo-container">
            <h1 class="stemwijzer-logo">
                <span>Politieke</span> <span>Stemwijzer</span>
            </h1>
            <img src="../assets/vote.svg" alt="participate checklist" />
        </div>
        <a class="logout-button" href="statements.php">Terug</a>
    </header>
    <hr>

    <form method="POST">
        <div class="container">
            <div class="stelling-beheer">
                <h2>Stelling beheer</h2>
                <textarea class="uitleg-stellingen">Prioriteit vraag is een vraag die belangrijk is en waarvan de antwoorden zwaarder wegen dan andere vragen. 2x zoveel punten.</textarea>
            </div>
            <div class="main-content">
                <div>
                    <label for="title">Titel</label>
                    <input type="text" id="title" name="title">
                </div>
                <div>
                    <label for="description">Beschrijving</label>
                    <textarea id="description" name="description"></textarea>
                </div>
                <button type="submit">Aanmaken</button>
                <p class="errorMessage"></p>
            </div>
            <div class="sidebar">
                <div>
                    <label for="x-as">Links & Rechts waarde</label>
                    <select id="x-as" name="x-as">
                        <option value="-5">5 punten, Links</option>
                        <option value="-4">4 punten, Links</option>
                        <option value="-3">3 punten, Links</option>
                        <option value="-2">2 punten, Links</option>
                        <option value="-1">1 punten, Links</option>
                        <option value="0" selected>0 punten, Geen van beide</option>
                        <option value="1">1 punten, Rechts</option>
                        <option value="2">2 punten, Rechts</option>
                        <option value="3">3 punten, Rechts</option>
                        <option value="4">4 punten, Rechts</option>
                        <option value="5">5 punten, Rechts</option>
                    </select>
                </div>
                <div>
                    <label for="y-as">Conservatief & Progressief waarde</label>
                    <select id="y-as" name="y-as">
                        <option value="-5">5 punten, Conservatief</option>
                        <option value="-4">4 punten, Conservatief</option>
                        <option value="-3">3 punten, Conservatief</option>
                        <option value="-2">2 punten, Conservatief</option>
                        <option value="-1">1 punten, Conservatief</option>
                        <option value="0" selected>0 punten, Geen van beide</option>
                        <option value="1">1 punten, Progressief</option>
                        <option value="2">2 punten, Progressief</option>
                        <option value="3">3 punten, Progressief</option>
                        <option value="4">4 punten, Progressief</option>
                        <option value="5">5 punten, Progressief</option>
                    </select>
                </div>
                <div>
                    <label>
                        <input type="checkbox" id="priority" name="priority">
                        Prioriteit vraag
                    </label>
                </div>
            </div>
        </div>
    </form>

<script src="../js/api.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const xValue = document.getElementById('x-as').value;
        const yValue = document.getElementById('y-as').value;
        const priority = document.getElementById('priority').checked ? 1 : 0;
        const errorMessage = document.querySelector('.errorMessage');

        if (!title || !description) {
            errorMessage.textContent = 'Vul alle verplichte velden in';
            return;
        }

        addStatement(title, description, xValue, yValue, priority)
        .then(response => {
            errorMessage.textContent = 'Stelling succesvol toegevoegd';
            setTimeout(() => window.location.href = 'statements.php', 1000);
        })
        .catch(error => {
            errorMessage.textContent = error.message;
        });


    });
});
</script>
</body>
</html>