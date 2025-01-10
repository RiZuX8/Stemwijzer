<?php
session_start();
if (!isset($_SESSION['admin'])) {
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
    <title>Stellingen</title>
    <link rel="stylesheet" href="../styles/superadminbeheer.css">
    <link rel="stylesheet" href="../styles/algemeen.css">
    <link rel="stylesheet" href="../styles/stellingen_answer.css">
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
            <th style="text-align: left; padding: 15px;">Stellingen</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($statements as $statement) {
        echo "<tr data-statement-id='{$statement['statementID']}'>
            <td class='statement-container'>
                <div class='statement-text'>{$statement['name']}</div>
                <div class='vote-buttons'>
                    <button class='vote-btn disagree-btn' data-value='disagree'>Oneens</button>
                    <button class='vote-btn neutral-btn' data-value='neither'>Geen van beide</button>
                    <button class='vote-btn agree-btn' data-value='agree'>Eens</button>
                </div>
            </td>
        </tr>";
    }
    ?>
    </tbody>
</table>

<div class="pagination">
    <button onclick="changePage(1)" <?php echo $page <= 1 ? 'disabled' : ''; ?>><<</button>
    <button onclick="changePage(<?php echo $page - 1; ?>)" <?php echo $page <= 1 ? 'disabled' : ''; ?>><</button>

    <?php
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
async function handleVote(button, statementID) {
    // Prevent clicking already selected button
    if (button.classList.contains('active-vote')) {
        return;
    }

    const partyID = <?php echo $_SESSION['admin']['partyID']; ?>;
    const answerValue = button.dataset.value;
    const row = button.closest('tr');
    const buttons = row.querySelectorAll('.vote-btn');

    // Clear existing states first
    buttons.forEach(btn => btn.classList.remove('active-vote', 'inactive-vote'));

    try {
        // Try to update first since it's more likely we're updating an existing vote
        try {
            await updatePartyStatement(partyID, statementID, answerValue);
        } catch (updateError) {
            // If update fails (probably because it doesn't exist yet), try to add
            await addPartyStatement(partyID, statementID, answerValue);
        }

        // Apply new states after successful API call
        buttons.forEach(btn => {
            if (btn === button) {
                btn.classList.add('active-vote');
            } else {
                btn.classList.add('inactive-vote');
            }
        });
    } catch (error) {
        console.error('Error handling vote:', error);
        // Reload answers to ensure UI is in sync with server state
        await loadPartyAnswers();
        alert('Er is een fout opgetreden bij het opslaan van uw stem.');
    }
}

async function loadPartyAnswers() {
    const partyID = <?php echo $_SESSION['admin']['partyID']; ?>;
    try {
        const answers = await getPartyStatement(partyID);
        // First clear any existing vote states
        document.querySelectorAll('.vote-btn').forEach(btn => {
            btn.classList.remove('active-vote', 'inactive-vote');
        });
        
        if (Array.isArray(answers)) {
            answers.forEach(answer => {
                const row = document.querySelector(`tr[data-statement-id="${answer.statementID}"]`);
                if (row) {
                    const buttons = row.querySelectorAll('.vote-btn');
                    buttons.forEach(btn => {
                        if (btn.dataset.value === answer.answerValue) {
                            btn.classList.add('active-vote');
                        } else {
                            btn.classList.add('inactive-vote');
                        }
                    });
                }
            });
        }
    } catch (error) {
        // Clear any existing vote states and ensure buttons are clickable
        document.querySelectorAll('.vote-btn').forEach(btn => {
            btn.classList.remove('active-vote', 'inactive-vote');
        });
        
        // Only log error if it's not a 404 (no answers yet)
        if (!error.message.includes('404')) {
            console.error('Error loading party answers:', error);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadPartyAnswers();

    const voteButtons = document.querySelectorAll('.vote-btn');
    voteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const statementID = this.closest('tr').dataset.statementId;
            handleVote(this, statementID);
        });
    });
});

function changePage(pageNum) {
    const url = new URL(window.location.href);
    url.searchParams.set('page', pageNum);
    window.location.href = url.toString();
}
</script>
</body>
</html>
