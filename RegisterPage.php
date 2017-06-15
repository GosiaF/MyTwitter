<?php
require_once 'Classes/ClassUser.php';
require_once 'DBconnect.php';

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['username'])) {

        $mail = $_POST['email'];
        $pas = $_POST['password'];
        $uName = $_POST['username'];
// Sprawdzenie czy w bazie istnieje już taki mail i username, jeżeli nie powrót do RegisterPage
        if (User::loadUserByMail($conn, $mail) !== null || User::loadUserByUsername($conn, $uName) !== null) {
            $_SESSION['userExist'] = true;
            header("location: RegisterPage.php");
        }
//stworzenie obiektu i ustawienie danych, następnie zapisanie użytkownika do bazy danych
        $user = new User();
        $user->setEmail($mail);
        $user->setUsername($uName);
        $user->setHashPass($pas);
        $a = $user->saveToDB($conn);

//Logowanie
        $sql = "SELECT username, id, email FROM users WHERE email ='$mail' and hash_pass = '$pas'";

        $result = $conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $count = count($result);

        if ($row != false) {
            $_SESSION['login_id'] = $row['id'];
            $_SESSION['login_username'] = $row['username'];
            $_SESSION['login_email'] = $row['email'];
            header("location: HomePage.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<?php
if ($_SESSION['userExist'] == true) {
    echo "Taki user już istnieje. Wprowadź inne dane.";
    $_SESSION['userExist'] == false;
}
?>
<br>Jeżeli posiadasz już konto, przejdź do strony logowania:
<a href="LoginPage.php">Strona logowania</a>
<h2>Rejestracja</h2>
<form action="" method="post">
    Podaj nazwę użytkownika :
    <input type="text" name="username"> </input>
    Podaj adres email :
    <input type="text" name="email"> </input>
    Podaj hasło :
    <input type="text" name="password"> </input>
    <button type="submit">Zarejestruj się</button>
</form>
</body>
</html>