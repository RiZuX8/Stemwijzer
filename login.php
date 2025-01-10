<?php
session_start();
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['admin'])) {
    header('Location: admin/home.php');
    exit();
}
if (isset($_SESSION['superAdmin'])) {
    header('Location: superadmin/home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles/login.css">
    <title>Login</title>
</head>

<body>
    <div class="main-container">
        <header>
            <h1 class="stemwijzer-logo">
                <span>Politieke</span> <span>Stemwijzer</span>
            </h1>
            <img src="./assets/vote.svg" alt="participate checklist" />
        </header>
        <div class="login-container">
            <form method="POST">
                <label for="email">E-mailadres:</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Wachtwoord:</label>
                <input type="password" name="password" id="password" required>

                <button type="submit">Inloggen</button>
                <p class="errorMessage"></p>
            </form>
        </div>
    </div>
</body>

</html>


<?php
require_once 'classes/Admin.php';
require_once 'classes/SuperAdmin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $admin = new Admin();
    $superAdmin = new SuperAdmin();
    if (!empty($email) and !empty($password)) {
        // check if admin exists by email
        if ($admin->getByEmail($email)) {
            // check if password is correct
            if ($admin->login($email, $password)) {
                $_SESSION['admin'] = [
                    'id' => $admin->adminID,
                    'partyID' => $admin->partyID,
                    'email' => $admin->email
                ];
                error_log('Admin ingelogd');
                error_log(print_r($_SESSION, true));
                header('Location: admin/home.php');
            } else {
                error_log('E-mailadres en/of wachtwoord is onjuist');
                showErrorMessage('E-mailadres en/of wachtwoord is onjuist');
            }
        } else if ($superAdmin->getByEmail($email)) {
            // check if superAdmin exists by email
            // check if password is correct
            if ($superAdmin->login($email, $password)) {
                $_SESSION['superAdmin'] = [
                    'id' => $superAdmin->superAdminID,
                    'email' => $superAdmin->email
                ];
                error_log('SuperAdmin ingelogd');
                error_log(print_r($_SESSION, true));
                header('Location: superadmin/home.php');
            } else {
                error_log('E-mailadres en/of wachtwoord is onjuist');
                showErrorMessage('E-mailadres en/of wachtwoord is onjuist');
            }
        } else {
            error_log('Admin of SuperAdmin bestaat niet');
            showErrorMessage('E-mailadres en/of wachtwoord is onjuist');
        }
    } else {
        error_log('Vul alle velden in');
        showErrorMessage('Vul alle velden in');
    }
}

function showErrorMessage($message)
{
    echo '<script>document.querySelector(".errorMessage").innerText = "' . $message . '";</script>';
}
?>