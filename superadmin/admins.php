<?php
session_start();
if (!isset($_SESSION['superAdmin'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../classes/Admin.php';
require_once '../classes/Party.php';

$admin = new Admin();
$admins = $admin->getAll();

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
    <title>Admin beheer</title>
    <link rel="stylesheet" href="../styles/superadminbeheer.css">
    <link rel="stylesheet" href="../styles/algemeen.css">
</head>
<body>
<header>
    <div class="logo-container">
        <h1 class="stemwijzer-logo_text">
            <span>Politieke</span> <span>Stemwijzer</span>
        </h1>
        <svg class="logo_svg__logo" width="34" height="34" viewBox="0 0 84 81" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <title>Stemwijzer Icon</title>
            <path d="M45.8151 37.1399C46.7697 37.1399 47.5507 36.384 47.5507 35.4602V22.0222C47.5507 21.0983 46.7697 20.3424 45.8151 20.3424H23.2513C22.2967 20.3424 21.5156 21.0983 21.5156 22.0222V35.4602C21.5156 36.384 22.2967 37.1399 23.2513 37.1399H45.8151ZM24.987 23.7019L44.0794 23.6683L44.01 33.7804H24.987V23.7019Z" fill="#0A2750" />
            <path d="M72.8222 51.317C72.3535 50.8635 71.694 50.5779 70.965 50.5779H57.9648V13.5898C57.9648 11.7421 56.42 10.2639 54.5282 10.2639H13.7051C12.2645 10.2639 11.1016 11.3893 11.1016 12.7835V68.2153C11.1016 69.6095 12.2645 70.735 13.7051 70.735H26.7226C27.6773 70.735 28.4583 69.9959 28.4583 69.072C28.4583 68.1313 27.6773 67.3755 26.7226 67.3755H14.5729V13.6234H54.4934V50.5947H37.3797C36.8763 50.6115 36.373 50.7627 35.9564 51.0315L22.4008 59.5982C21.7065 60.0517 21.6371 60.9924 22.2099 61.5467C22.262 61.5971 22.3314 61.6643 22.4008 61.6979L35.9391 70.315C36.3904 70.6006 36.9111 70.7517 37.4491 70.7517H70.9824C72.423 70.7517 73.5859 69.6263 73.5859 68.2321V53.1312C73.5859 52.4257 73.2908 51.8042 72.8222 51.3338V51.317ZM37.1367 67.3755L30.194 62.9913V58.2712L37.1367 53.9374V67.3755ZM70.1145 67.3755H40.608V62.3362H70.1145V67.3755ZM70.1145 58.9767H40.608V53.9374H70.1145V58.9767Z" fill="#0A2750" />
        </svg>
    </div>
    <a class="logout-button" href="home.php">Terug</a>
</header>
<hr>

<table>
    <thead>
    <tr>
        <th class="number-col">ID</th>
        <th class="text-area-col">Partij</th>
        <th class="text-area-col">Email</th>
        <th class="action-col"></th>
        <th class="action-col"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($admins as $admin) {
        $partyName = '';
        foreach ($parties as $party) {
            if ($party['partyID'] == $admin['partyID']) {
                $partyName = $party['name'];
                break;
            }
        }
        echo "<tr>
            <td class='number-col'><div class='mainNumber'>{$admin['adminID']}</div></td>
            <td class='text-area-col'>
                <input type='text' class='mainInfo party-input' value='$partyName' disabled>
                <div class='party-select-wrapper' style='display:none;'>
                    <select class='party-select'>
                        " . implode('', array_map(function($party) use ($admin) {
                            return "<option value='{$party['partyID']}'" . ($party['partyID'] == $admin['partyID'] ? ' selected' : '') . ">{$party['name']}</option>";
                        }, $parties)) . "
                    </select>
                </div>
            </td>
            <td class='text-area-col'><input type='text' class='mainInfo' value='{$admin['email']}' disabled></td>
            <td class='action-col'><button class='edit-btn'>Aanpassen</button></td>
            <td class='action-col'><button class='remove-btn'>Verwijderen</button></td>
            </tr>";
    }
    ?>
    </tbody>
</table>

<script>
    function handleButtonClick(event) {
        const button = event.target;
        const row = button.closest('tr');
        const inputFields = row.querySelectorAll('.mainInfo');
        const partyInput = row.querySelector('.party-input');
        const partySelectWrapper = row.querySelector('.party-select-wrapper');

        if (button.classList.contains('edit-btn')) {
            // Change to Save state
            button.textContent = 'Opslaan';
            button.classList.remove('edit-btn');
            button.classList.add('save-btn');
            inputFields.forEach(input => {
                if (input !== partyInput) {
                    input.disabled = false;
                }
            });
            partyInput.style.display = 'none';
            partySelectWrapper.style.display = 'inline-block';
        } else if (button.classList.contains('save-btn')) {
            // Change back to Edit state
            button.textContent = 'Aanpassen';
            button.classList.remove('save-btn');
            button.classList.add('edit-btn');
            inputFields.forEach(input => input.disabled = true);
            const partySelect = partySelectWrapper.querySelector('.party-select');
            partyInput.value = partySelect.options[partySelect.selectedIndex].text;
            partyInput.style.display = 'inline-block';
            partySelectWrapper.style.display = 'none';

            //updateAdminData(row.querySelector('.mainNumber').textContent, partySelect.value, row.querySelector('input[type="text"]:nth-child(2)').value);
        }
    }

    function updateAdminData(adminId, partyId, email) {
        // Send request to update the data
    }

    const buttons = document.querySelectorAll('.edit-btn, .save-btn');

    buttons.forEach(button => {
        button.addEventListener('click', handleButtonClick);
    });
</script>
</body>
</html>