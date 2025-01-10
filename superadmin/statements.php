<?php
session_start();
if (!isset($_SESSION['superAdmin'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../classes/Statement.php';

$statement = new Statement();
$all_statements = $statement->getAll();

// Pagination settings
$records_per_page = 5;
$total_records = count($all_statements);
$total_pages = ceil($total_records / $records_per_page);
$page = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $total_pages)) : 1;
$offset = ($page - 1) * $records_per_page;

// Slice the array for current page
$statements = array_slice($all_statements, $offset, $records_per_page);
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stellingen beheer</title>
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
    Totaal aantal stellingen: <?php echo $total_records; ?> | Pagina <?php echo $page; ?> van <?php echo $total_pages; ?>
</div>

<table>
    <thead>
    <tr>
        <th class="number-col">ID</th>
        <th class="text-area-col">Naam</th>
        <th class="text-area-col">Beschrijving</th>
        <th class="action-col"></th>
        <th class="action-col"></th>
        <th class="add-col"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($statements as $index => $statement) {
        echo "<tr data-statementid='{$statement['statementID']}'>
            <td class='number-col'><div class='mainNumber'>{$statement['statementID']}</div></td>
            <td class='text-area-col'><input type='text' class='mainInfo' value='{$statement['name']}' disabled></td>
            <td class='text-area-col'><input type='text' class='mainInfo' value='{$statement['description']}' disabled></td>
            <td class='action-col'>
                <button class='edit-btn' onclick=\"window.location.href='edit_statement.php?id={$statement['statementID']}'\">
                    Aanpassen
                </button>
            </td>
            <td class='action-col'><button class='remove-btn'>Verwijderen</button></td>
            <td class='add-col'>" . ($index === 0 ? "<button class='add-btn' onclick=\"window.location.href='add_statement.php'\">+</button>" : "") . "</td>
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
    function handleButtonClick(event) {
        const button = event.target;
        const row = button.closest('tr');

        if (button.classList.contains('remove-btn')) {
            const statementID = row.dataset.statementid;
            if (confirm(`Weet je zeker dat je de stelling met ID ${statementID} wilt verwijderen?`)) {
                deleteStatement(statementID)
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
                        alert('Er is een fout opgetreden bij het verwijderen van de stelling. Probeer het opnieuw.');
                    });
            }
        }
    }

    const buttons = document.querySelectorAll('.remove-btn');
    buttons.forEach(button => {
        button.addEventListener('click', handleButtonClick);
    });
});
</script>
</body>
</html>