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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Partij beheer</title>
    <link rel="stylesheet" href="../styles/algemeen.css">
    <link rel="stylesheet" href="../styles/admin_party_edit.css">
</head>
<body>
<header>
    <div class="logo-container">
        <h1 class="stemwijzer-logo_text">
            <span>Politieke</span> <span>Stemwijzer</span>
        </h1>
        <img src="../assets/vote.svg" alt="participate checklist" />
    </div>
    <a class="logout-button" href="home.php">Terug</a>
</header>
<hr>

<div class="container">
    <h2 class="title">Partij aanpassen</h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="edit-content">
            <div class="image-upload-section">
                <label for="file-upload" class="image-container">
                    <img id="image-preview"
                         src="<?php echo $partyData['image']; ?>"
                         alt="Partij logo"
                         style="display: <?php echo $partyData['image'] ? 'block' : 'none'; ?>; width: 100%; height: 100%; object-fit: cover;">
                    <div class="image-placeholder" style="display: <?php echo $partyData['image'] ? 'none' : 'block'; ?>">
                        Afbeelding toevoegen
                    </div>
                    <button type="button" class="add-image-button">+</button>
                </label>
                <input type="hidden" name="partyID" value="<?php echo $partyID; ?>">
                <input type="file" id="file-upload" name="image" accept="image/*">
            </div>

            <div class="form-section">
                <div class="form-group">
                    <label for="name">Partij naam</label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-input"
                           value="<?php echo $partyData['name']; ?>"
                           required>
                </div>

                <div class="form-group">
                    <label for="description">Beschrijving</label>
                    <textarea id="description"
                              name="description"
                              class="form-input"
                              required><?php echo $partyData['description']; ?></textarea>
                </div>

                <button type="submit" class="submit-button">Opslaan</button>
                <p class="error-message"></p>
            </div>
        </div>
    </form>
</div>

<script src="../js/api.js"></script>
<script>
    const form = document.querySelector('form');
    const errorMessage = document.querySelector('.error-message');
    const fileUpload = document.querySelector('#file-upload');
    const imagePreview = document.querySelector('#image-preview');
    const uploadPlaceholder = document.querySelector('.image-placeholder');
    const addImageButton = document.querySelector('.add-image-button');

    addImageButton.addEventListener('click', (e) => {
        e.preventDefault();
        fileUpload.click();
    });

    fileUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                uploadPlaceholder.style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const partyID = document.querySelector('input[name="partyID"]').value;
        const name = document.querySelector('#name').value;
        const description = document.querySelector('#description').value;
        const file = fileUpload.files[0];
        const currentImage = '<?php echo $imageName; ?>';

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
            formData.append('image', currentImage);
        }

        try {
            const response = await updatePartyWithImage(partyID, formData);
            errorMessage.textContent = 'Partij succesvol bijgewerkt.';
            errorMessage.style.color = '#28a745';
            setTimeout(() => window.location.href = 'home.php', 1000);
        } catch (error) {
            errorMessage.textContent = error.message;
        }
    });
</script>
</body>
</html>