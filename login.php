<?php
session_start();
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: /login.php');
    exit;
}
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <span class="errorMessage"></span>
    <form method="post">
        <label for="email">E-mailadres:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="password">Wachtwoord:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit">Inloggen</button>
    </form>
</body>
</html>


<?php
require_once 'classes/Admin.php';
require_once 'classes/SuperAdmin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (!empty($email) AND !empty($password)) {
        $admin = new Admin();
        // check if admin exists by email
        $checkAdmin = $admin->getByEmail($email);
        if ($checkAdmin['status'] === 200) {
            // check if password is correct
            if ($admin->login($email, $password)) {
                $_SESSION['admin'] = [
                    'id' => $admin->adminID,
                    'partyID' => $admin->partyID,
                    'email' => $admin->email
                ];
                error_log('Admin ingelogd');
                error_log(print_r($_SESSION, true));
                header('Location: /admin.php');
            } else {
                error_log('E-mailadres en/of wachtwoord is onjuist');
                showErrorMessage('E-mailadres en/of wachtwoord is onjuist');
            }
        } else {
            error_log('Admin bestaat niet');
            // check if superAdmin exists by email
            $superAdmin = new SuperAdmin();
            if ($superAdmin->getByEmail($email)) {
                // check if password is correct
                if ($superAdmin->login($email, $password)) {
                    $_SESSION['superAdmin'] = [
                        'id' => $superAdmin->superAdminID,
                        'email' => $superAdmin->email
                    ];
                    error_log('SuperAdmin ingelogd');
                    error_log(print_r($_SESSION, true));
                    header('Location: /superadmin.php');
                } else {
                    error_log('E-mailadres en/of wachtwoord is onjuist');
                    showErrorMessage('E-mailadres en/of wachtwoord is onjuist');
                }
            }
        }
    } else {
        error_log('Vul alle velden in');
        showErrorMessage('Vul alle velden in');
    }
}

function showErrorMessage($message) {
    echo '<script>document.querySelector(".errorMessage").innerText = "' . $message . '";</script>';
}
?>