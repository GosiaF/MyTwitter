<?php
include_once 'Classes/ClassComment.php';
include_once 'DBconnect.php';

session_start();
$id = $_SESSION['login_userId'];
$tweetId = $_SESSION['tweetId'];

$dateOb = new DateTime();
$dateOb->modify('now');
$date = $dateOb->format('Y-m-d H:i:s');

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
echo "komentarz został dodany!";

echo "<a href='DisplayPost.php'>Wróć do poprzedniej strony</a>";