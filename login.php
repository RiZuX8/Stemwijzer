<?php
session_start();
require_once 'classes/Admin.php';
require_once 'classes/SuperAdmin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (isset($email) AND isset($password)) {
        $admin = new Admin();
        // check if admin exists by email
        if ($admin->getByEmail($email)) {
            // check if password is correct
            if ($admin->login($email, $password)) {
                $_SESSION['admin'] = [
                    'id' => $admin->adminID,
                    'partyID' => $admin->partyID,
                    'email' => $admin->email
                ];
                header('Location: /admin.php');
                exit;
            } else {
                $error = 'E-mailadres en/of wachtwoord is onjuist';
                error_log($error);
            }
        } else {
            // check if superAdmin exists by email
            $superAdmin = new SuperAdmin();
            if ($superAdmin->getByEmail($email)) {
                // check if password is correct
                if ($superAdmin->login($email, $password)) {
                    $_SESSION['superAdmin'] = [
                        'id' => $superAdmin->superAdminID,
                        'email' => $superAdmin->email
                    ];
                    header('Location: /superadmin.php');
                    exit;
                } else {
                    $error = 'E-mailadres en/of wachtwoord is onjuist';
                    error_log($error);
                }
            }
        }
    } else {
        $error = 'Vul alle velden in';
        error_log($error);
    }
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