<?php
include_once 'Classes/ClassTweet.php';
include_once 'Classes/ClassComment.php';
include_once 'Classes/ClassUser.php';
include_once 'DBconnect.php';
session_start();

$dateOb = new DateTime();
$dateOb->modify('now');
$date = $dateOb->format('Y-m-d H:i:s');

$tweetId = $_GET['tweetId'];

$username = $_SESSION['login_username'];
$id = $_SESSION['login_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id']) && $_GET['username'] && $_GET['tweetId']) {
        $userId = $_GET['id'];
        $username = $_GET['username'];
        $tweetId = $_GET['tweetId'];
        $_SESSION['tweetId'] = $tweetId;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['comment'])){
        $comment = $_POST['comment'];

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
    <title>Title</title>
</head>
<body>
<p>
    Informacje o poście :<br><br>
<?php
    $tweet = Tweet::loadTweetById($conn, $tweetId);
    $date = $tweet->getCreationDate();
    $text = $tweet->getText();

    echo "Data utworzenia posta : " . $date . "<br>";
    echo "Tweet został dodany prze użytkownika : " . $username . "<br>";
    echo "Tweet o treści : " . $text . "<br><br>";

    $postId = $tweetId;

    $commentsByPostId = Comment::loadAllCommentsByPostId($conn, $postId);

    echo "Wszystkie komentarze do postu :<br><br>";
    foreach ($commentsByPostId as $post){

        $uId = $post->getUserId();
        $user = User::loadUserById($conn, $uId);
        $nameUser = $user->getUsername();
        echo "Komentarz od : " . $nameUser . "<br>";
        echo "Komentarz dodany : " . $date . "<br>";
        echo "Treść komentarza : " . $post->getText() . "<br><br>";
    }
echo "Dodaj nowy komentarz<br>";
echo "<form action='' method='post'>";
echo "<input name='comment'>";
echo "<input type='hidden' name='tweetId' value='$tweetId'>";
echo "<button type='submit'>Dodaj</button>";
echo "</form>";
echo "<br><br>";

?>
</p>

</body>
</html>