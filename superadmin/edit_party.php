<?php
session_start();
if (!isset($_SESSION['superAdmin'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../classes/Party.php';

$party = new Party();
$partyID = $_GET['id'] ?? null;

if (!$partyID) {
    header('Location: partijen.php');
    exit();
}

$partyData = $party->getById($partyID);
if (!$partyData) {
    header('Location: partijen.php');
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
    <title>Partij bewerken</title>
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
    <a class="logout-button" href="partijen.php">Terug</a>
</header>
<hr>

<div class="container">
    <h2 class="title">Partij bewerken</h2>
    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="partyID" id="partyID" value="<?php echo $partyData['partyID']; ?>">

            <label for="name">Naam:</label>
            <input type="text" name="name" id="name" value="<?php echo $partyData['name']; ?>" required>

            <label for="description">Beschrijving:</label>
            <input type="text" name="description" id="description" value="<?php echo $partyData['description']; ?>" required>

            <p id="current-image">Huidige afbeelding: <?php echo $imageName; ?></p>

            <label for="file-upload" class="custom-file-upload" id="upload-label">
                Kies een nieuwe afbeelding
            </label>
            <input type="file" name="image" id="file-upload" accept="image/*">
            <img id="image-preview" class="image-preview" style="display: none;">

            <button type="submit">Wijzigingen opslaan</button>
            <p class="errorMessage"></p>
        </form>
    </div>
</div>

<script src="../js/api.js"></script>
<script>
    const form = document.querySelector('form');
    const errorMessage = document.querySelector('.errorMessage');
    const fileUpload = document.querySelector('#file-upload');
    const imagePreview = document.querySelector('#image-preview');
    const uploadLabel = document.querySelector('#upload-label');

    fileUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            uploadLabel.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const partyID = document.querySelector('#partyID').value;
        const name = document.querySelector('#name').value;
        const description = document.querySelector('#description').value;
        const file = fileUpload.files[0];
        const currentImage = document.querySelector('#current-image')
            .textContent
            .replace('Huidige afbeelding: ', '')
            .trim();

        if (name === '') {
            errorMessage.textContent = 'Vul een naam in.';
            return;
        }
        if (description === '') {
            errorMessage.textContent = 'Vul een beschrijving in.';
            return;
        }

        let formData = new FormData();
        formData.append('name', name);
        formData.append('description', description);
        if (file) {
            formData.append('image', file);
        } else {
            // Gebruik de huidige afbeelding
            formData.append('image', currentImage);
        }

        try {
            const response = await updatePartyWithImage(partyID, formData);
            errorMessage.textContent = 'Partij succesvol bijgewerkt.';
            setTimeout(() => window.location.href = 'partijen.php', 1000);
        } catch (error) {
            errorMessage.textContent = error.message;
        }
    });
</script>

</body>
</html>