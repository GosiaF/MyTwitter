<?php
include_once 'DBconnect.php';

   session_start();
   if($_SERVER["REQUEST_METHOD"] == "POST") {

       $myEmail = $_POST['email'];
       $myPassword = $_POST['password'];

       $sql = "SELECT username, id, email FROM users WHERE email ='$myEmail' and hash_pass = '$myPassword'";
       $result = $conn->query($sql);
       $row = $result->fetch(PDO::FETCH_ASSOC);
       $count = count($result);


       if($row != false) {
           $_SESSION['login_username'] = $row['username'];
           $_SESSION['login_id'] = $row['id'];
           $_SESSION['login_email'] = $row['email'];
           header("location: HomePage.php");
       }else {
           $error = "Błednie wprowadzony login lub hasło. Spróbuj jeszcze raz.";
           echo $error;
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
<br>Jeżeli nie posiadasz konta, to przejdź do strony rejestracji:
<a href="RegisterPage.php">Rejestracja</a>
<h2>Logowanie</h2>
<form action="" method="post">
    Podaj adres email :
    <input type="text" name="email"> </input>
    Podaj hasło :
    <input type="text" name="password"> </input>
    <button type="submit">Zaloguj się</button>
</form>
</body>
</html>