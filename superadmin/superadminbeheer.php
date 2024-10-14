
<?php
session_start();
if (!isset($_SESSION['superAdmin'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../classes/Admin.php';
require_once '../classes/Party.php';

$admin = new Admin();
$result = $admin->getAll();

$party = new Party();
$parties = $party->getAll();


?>
<?php
$phpArray = array('apple', 'banana', 'cherry');
?>

<script>
    let jsArray = <?php echo json_encode($phpArray); ?>;
    console.log(jsArray);
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Table</title>
    <link href='https://fonts.googleapis.com/css?family=Atkinson Hyperlegible' rel='stylesheet'>
    <link rel="stylesheet" href="../styles/superadminbeheer.css">
    <link rel="stylesheet" href="../styles/algemeen.css">
</head>
<body>
<table>
    <thead>
    <tr>
        <th class="number-col">Rij</th>
        <th class="text-area-col">Partij</th>
        <th class="text-area-col">Email</th>
        <th class="action-col"></th>
        <th class="action-col"></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="number-col"><div class="mainNumber">1</div></td>
        <td class="text-area-col"><input type="text" class="mainInfo" disabled ></td>
        <td class="text-area-col"><input type="text" class="mainInfo" disabled></td>
        <td class="action-col"><button class="edit-btn">Aanpassen</button></td>
        <td class="action-col"><button class="remove-btn">Verwijderen</button></td>
    </tr>
    <tr>
        <td class="number-col"><div class="mainNumber">1</div></td>
        <td class="text-area-col"><input type="text" class="mainInfo" disabled ></td>
        <td class="text-area-col"><input type="text" class="mainInfo" disabled></td>
        <td class="action-col"><button class="edit-btn">Aanpassen</button></td>
        <td class="action-col"><button class="remove-btn">Verwijderen</button></td>
    </tr>
    <tr>
        <td class="number-col"><div class="mainNumber">1</div></td>
        <td class="text-area-col"><input type="text" class="mainInfo" disabled ></td>
        <td class="text-area-col"><input type="text" class="mainInfo" disabled></td>
        <td class="action-col"><button class="edit-btn">Aanpassen</button></td>
        <td class="action-col"><button class="remove-btn">Verwijderen</button></td>
    </tr>
    </tbody>
</table>
<script>

    // Function to handle button click event
    function handleButtonClick(event) {
        const button = event.target;
        const row = button.closest('tr');
        console.log(row); // Get the closest row
        const inputFields = row.querySelectorAll('.mainInfo'); // Get all input fields in the same row
        console.log(inputFields);
        if (button.classList.contains('edit-btn')) {
            // Change to Save state
            button.textContent = 'Opslaan';
            button.classList.remove('edit-btn');
            button.classList.add('save-btn');
            inputFields.forEach(input => input.disabled = false);

        } else if (button.classList.contains('save-btn')) {
            // Change back to Edit state
            button.textContent = 'Aanpassen';
            button.classList.remove('save-btn');
            button.classList.add('edit-btn');
            inputFields.forEach(input => input.disabled = true);
        }
    }

    // Get all buttons with the class 'edit-btn' and 'save-btn'
    const buttons = document.querySelectorAll('.edit-btn, .save-btn');

    // Add click event listener to each button
    buttons.forEach(button => {
        button.addEventListener('click', handleButtonClick);
    });
</script>
</body>
</html>
