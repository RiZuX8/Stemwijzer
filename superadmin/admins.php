<?php
session_start();
if (!isset($_SESSION['superAdmin'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../classes/Admin.php';
require_once '../classes/Party.php';

$admin = new Admin();
$party = new Party();

$all_admins = $admin->getAll();
$parties = $party->getAll();

// Pagination settings
$records_per_page = 5;
$total_records = count($all_admins);
$total_pages = ceil($total_records / $records_per_page);
$page = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $total_pages)) : 1;
$offset = ($page - 1) * $records_per_page;

// Slice the array for current page
$admins = array_slice($all_admins, $offset, $records_per_page);
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
    </div>
    <a class="logout-button" href="home.php">Terug</a>
</header>
<hr>

<div class="pagination-info">
    Totaal aantal admins: <?php echo $total_records; ?> | Pagina <?php echo $page; ?> van <?php echo $total_pages; ?>
</div>

<table>
    <thead>
    <tr>
        <th class="number-col">ID</th>
        <th class="text-area-col">Partij</th>
        <th class="text-area-col">Email</th>
        <th class="action-col"></th>
        <th class="action-col"></th>
        <th class="add-col"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($admins as $index => $admin) {
        $partyName = '';
        foreach ($parties as $party) {
            if ($party['partyID'] == $admin['partyID']) {
                $partyName = $party['name'];
                break;
            }
        }
        echo "<tr data-adminid='{$admin['adminID']}'>
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
            <td class='add-col'>" . ($index === 0 ? "<button class='add-btn' onclick=\"window.location.href='add_admin.php'\">+</button>" : "") . "</td>
        </tr>";
    }
    ?>
    </tbody>
</table>

<div class="pagination">
    <button onclick="changePage(1)" <?php echo $page <= 1 ? 'disabled' : ''; ?>><<</button>
    <button onclick="changePage(<?php echo $page - 1; ?>)" <?php echo $page <= 1 ? 'disabled' : ''; ?>><</button>

    <?php
    // Calculate range of pages to show
    $start_page = max(1, min($page - 2, $total_pages - 4));
    $end_page = min($total_pages, max(5, $page + 2));

    for ($i = $start_page; $i <= $end_page; $i++) {
        echo "<button onclick=\"changePage($i)\" " . ($i == $page ? 'class="active"' : '') . ">$i</button>";
    }
    ?>

    <button onclick="changePage(<?php echo $page + 1; ?>)" <?php echo $page >= $total_pages ? 'disabled' : ''; ?>>></button>
    <button onclick="changePage(<?php echo $total_pages; ?>)" <?php echo $page >= $total_pages ? 'disabled' : ''; ?>>>></button>
</div>

<script src="../js/api.js"></script>
<script>
function changePage(pageNum) {
    const url = new URL(window.location.href);
    url.searchParams.set('page', pageNum);
    window.location.href = url.toString();
}

document.addEventListener('DOMContentLoaded', () => {
    function changeButtonState(button, isEditing) {
        const row = button.closest('tr');
        const inputFields = row.querySelectorAll('.mainInfo');
        const partyInput = row.querySelector('.party-input');
        const partySelectWrapper = row.querySelector('.party-select-wrapper');

        if (isEditing) {
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
        } else {
            button.textContent = 'Aanpassen';
            button.classList.remove('save-btn');
            button.classList.add('edit-btn');
            inputFields.forEach(input => input.disabled = true);
            const partySelect = partySelectWrapper.querySelector('.party-select');
            if (partySelect) {
                partyInput.value = partySelect.options[partySelect.selectedIndex].text;
            }
            partyInput.style.display = 'inline-block';
            partySelectWrapper.style.display = 'none';
        }
    }

    function handleButtonClick(event) {
        const button = event.target;
        const row = button.closest('tr');

        if (button.classList.contains('edit-btn')) {
            changeButtonState(button, true);
        } else if (button.classList.contains('save-btn')) {
            const adminID = row.dataset.adminid;
            const partySelect = row.querySelector('.party-select');
            const emailInput = row.querySelector('input.mainInfo:not(.party-input)');

            if (!adminID || !partySelect || !emailInput) {
                console.error('Required elements not found');
                alert('Er is een fout opgetreden. Controleer de console voor meer informatie.');
                return;
            }

            const newPartyID = partySelect.value;
            const newEmail = emailInput.value;
            const originalPartyID = partySelect.querySelector('option[selected]')?.value;
            const originalEmail = emailInput.defaultValue;

            if (newPartyID === originalPartyID && newEmail === originalEmail) {
                changeButtonState(button, false);
                return;
            }

            if (!newPartyID || !newEmail) {
                alert('Vul alle velden in.');
                return;
            }
            if (!newEmail.includes('@') || !newEmail.includes('.')) {
                alert('Vul een geldig emailadres in.');
                return;
            }

            updateAdmin(adminID, newPartyID, newEmail)
                .then(data => {
                    changeButtonState(button, false);
                    emailInput.defaultValue = newEmail;
                    partySelect.querySelector('option[selected]')?.removeAttribute('selected');
                    partySelect.querySelector(`option[value="${newPartyID}"]`)?.setAttribute('selected', '');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Er is een fout opgetreden bij het bijwerken van de gegevens. Probeer het opnieuw.');
                });
        } else if (button.classList.contains('remove-btn')) {
            const adminID = row.dataset.adminid;
            if (confirm(`Weet je zeker dat je de admin met ID ${adminID} wilt verwijderen?`)) {
                deleteAdmin(adminID)
                    .then(() => {
                        row.remove();
                        // Refresh als de laatste rij op de huidige pagina is verwijderd
                        const remainingRows = document.querySelectorAll('tbody tr').length;
                        if (remainingRows === 0) {
                            const currentPage = <?php echo $page; ?>;
                            if (currentPage > 1) {
                                changePage(currentPage - 1);
                            } else {
                                window.location.reload();
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Er is een fout opgetreden bij het verwijderen van de admin. Probeer het opnieuw.');
                    });
            }
        }
    }

    const buttons = document.querySelectorAll('.edit-btn, .save-btn, .remove-btn');
    buttons.forEach(button => {
        button.addEventListener('click', handleButtonClick);
    });
});
</script>
</body>
</html>