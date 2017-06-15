<?php
include_once 'Classes/ClassUser.php';
include_once 'Classes/ClassTweet.php';
include_once 'Classes/ClassComment.php';
include_once 'DBconnect.php';
session_start();

$username = $_SESSION['login_username'];
$id = $_SESSION['login_id'];
$mail = $_SESSION['login_email'];
var_dump($username);
var_dump($id);
var_dump($mail);



//ustawienie aktualnej daty pod zmienna
$dateOb = new DateTime("now");
$date = $dateOb->format('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['text'])) {

        $text = $_POST['text'];

        $user = new Tweet();
        $user->setUserId($id);
        $user->setText($text);
        $user->setCreationDate($date);
        $save = $user->saveToDB($conn);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['comment'])){
        $comment = $_POST['comment'];


        $tweetId = $_POST['tweetId'];



        Tweet::loadTweetById($conn, $tweetId);

        $newComment = new Comment();
        $newComment->setUserId($id);
        $newComment->setPostId($tweetId);
        $newComment->setCreationDate($date);
        $newComment->setText($comment);
        $newComment->saveToDB($conn);

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Strona glówna</title>
</head>
<body>
<?php
if ($_SESSION['change_email'] == true) {
    echo "Email zostal zmieniony";
    $_SESSION['change_email'] == false;
}
if ($_SESSION['change_username'] == true) {
    echo "Nazwa użytkonika zostala zmieniona";
    $_SESSION['change_email'] == false;

}
?>
<p>
    <?php echo "Jestes zalogowany jako użytkownik :" . $username;?>
</p>
<p>
    <a href="EditUserPage.php">Ustawienie danych logowania</a>
</p>
<form action="" method="post">
    Dodaj nowego tweeta :
    <input type="text" name="text">
    <button type="submit">Dodaj</button>
</form>
<br>
Tweety :
<br><br>
<?php

//Pobranie wszystkich tweetow
$AllTweets = Tweet::loadAllTweets($conn);

foreach ($AllTweets as $twet){

    $tweetId = $twet->getId();
    $user = $twet->getUserId();

//pobranie nazwy użytkownika
    $loadUser = User::loadUserById($conn, $user);
    $name = $loadUser->getUsername();

//link to strony wyswietlajacej inf o uzytkowniku
    echo "<a href='DisplayUserPage.php?id=$user&username=$name'>Użytkownik " . $name . "<br></a>";

//link do strony wyswietlajace informacje o poscie
    echo "Dodał ". "<a href='DisplayPost.php?id=$id&username=$name&tweetId=$tweetId'>tweeta</a>" . " o treści :" . $text = $twet->getText() . "<br>";
    echo "Data utworzenia tweeta : " . $date = $twet->getCreationDate();
    echo "<br><br>";

//wczytranie wszystkich komentarzy by postId
    $commentsByPostId = Comment::loadAllCommentsByPostId($conn, $tweetId);
//var_dump($commentsByPostId);
//die();
    echo "Wszystkie komentarze do postu :<br><br>";
    foreach ($commentsByPostId as $postNo => $post){

        $uId = $post->getUserId();


//pobranie username
        $user = User::loadUserById($conn, $uId);
        $nameUser = $user->getUsername();
        echo "Komentarz od : " . "<a href='DisplayUserPage.php?id=$id&username=$nameUser'> $nameUser</a>" . "<br>";
        echo "Treść komentarza : " . $post->getText() . "<br><br>";
    }

    echo "Dodaj nowy komentarz<br>";
    echo "<form action='' method='post'>";
    echo "<input name='comment'>";
    echo "<input type='hidden' name='tweetId' value='$tweetId'>";
    echo "<button type='submit'>Dodaj</button>";
    echo "</form>";
    echo "<br><br>";
}
?>
</body>
</html>