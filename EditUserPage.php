<?php
include_once 'Classes/ClassUser.php';
include_once 'DBconnect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['newName']) && isset($_POST['pass'])) {

        $user = User::loadUserById($conn, $_SESSION['login_id']);

        $hash = $user->getHashPass();
        $password = $_POST['pass'];
        $newName = $_POST['newName'];

        if ($hash === $password) {
            $newUser = User::loadUserByUsername($conn, $newName);
            if ($newUser === null) {

                $user->setUsername($newName);
                $user->saveToDB($conn);
                $_SESSION['change_username'] = true;

                header('Location: HomePage.php');
            } else {
                echo "Użytkownik o takiej nazwie już istnieje. Wybierz inną nazwę.";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['newEmail']) && isset($_POST['pass1'])) {

        $user = User::loadUserById($conn, $_SESSION['login_id']);

        $hash = $user->getHashPass();
        $password = $_POST['pass1'];
        var_dump($password);
        $newEmail = $_POST['newEmail'];
        var_dump($newEmail);
        if ($hash === $password) {

            $mailNew = User::loadUserByMail($conn, $newEmail);
            if ($mailNew === null) {

                $user->setEmail($newEmail);
                $user->saveToDB($conn);
                $_SESSION['change_email'] = true;
                header('Location: HomePage.php');
            } else {
                echo "Użytkownik o takim emailu już istnieje";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['newPass']) && isset($_POST['pass2'])) {

        $user = User::loadUserById($conn, $_SESSION['login_id']);

        $hash = $user->getHashPass();
        $password = $_POST['pass2'];
        var_dump($password);
        $newPass = $_POST['newPass'];
        var_dump($newPass);
        if ($hash === $password) {

            $passNew = User::loadUserByPass($conn, $newPass);
            if ($passNew === null) {

                $user->setHashPass($newPass);
                $user->saveToDB($conn);
                $_SESSION['change_pass'] = true;
                header('Location: HomePage.php');
            } else {
                echo "Haslo nie zostalo zmienione";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edycja Danych</title>
</head>
<body>
Zmiana nazwy użytkownika :
<form action="" method="post">
    Podaj nową nazwę użytkownika
    <input type="text" name="newName">
    Hasło
    <input type="text" name="pass">
    <button type="submit">Zmień nazwę użytkownika</button><br>
<br>Zmiana adresu email :<br>
<form action="" method="post">
        Podaj nowy adres email
        <input type="text" name="newEmail">
        Hasło
        <input type="text" name="pass1">
        <button type="submit">Zmień adres email</button>
</form>
<br>Zmiana hasła :<br>
    <form action="" method="post">
        Podaj nowe hasło
        <input type="text" name="newPass">
        Hasło
        <input type="text" name="pass2">
        <button type="submit">Zmień haslo</button>
    </form>
</body>
</html>