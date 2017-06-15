<?php
include_once 'Classes/ClassUser.php';
include_once 'Classes/ClassTweet.php';
include_once 'Classes/ClassComment.php';
include_once 'DBconnect.php';
session_start();
//$id = $_SESSION['login_userId'];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id']) && isset($_GET['username'])) {
        $id = $_GET['id'];
        $username = $_GET['username'];
    } else {
        echo "Brak danych!";
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
    <?php echo "Wszystkie posty użytkownika " . $username; ?>
</p>
<?php
$allTweets = Tweet::loadAllTweetsByUserId($conn, $id);
$countTweets = count($allTweets);

if ($countTweets <= 4) {
    echo "Użytkownik " . $username . " dodał w sumie " . $countTweets . " Tweety :<br><br>";
} else {
    echo "Użytkownik " . $username . " dodał w sumie " . $countTweets . " Tweetów :<br><br>";
}
foreach ($allTweets as $twet) {

    echo "Data utworzenia tweeta : " . $date = $twet->getCreationDate() . "<br>";
    echo "Treść tweeta :" . $text = $twet->getText() . "<br>";
    echo "<br>";
//    $postId = $twet->getId();
//    $postsByPostId = Comment::loadAllCommentsByPostId($conn, $postId);

//    foreach ($postsByPostId as $post){
//        echo "Komentarz od usera od id : " . $post->getUserId() . "<br>";
//        echo "Treść komentarza : " . $post->getText() . "<br><br>";
//    }
//    echo "Dodaj nowy komentarz<br>";
//    echo "<input>";
//    echo "<button>Dodaj</button>";
//    echo "<br><br>";
}

echo "<form action='sendMessage.php' method='get'>";
echo "<input type='hidden' name='userId' value='$id'>";
echo "<input type='hidden' name='username' value='$username'>";
echo "<button>Wyślij wiadomość do użytkownika</button>";
echo "</form>";
?>

