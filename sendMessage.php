<?php
include_once 'DBconnect.php';
include_once 'Classes/ClassComment.php';
include_once 'Classes/ClassUser.php';
include_once 'Classes/ClassTweet.php';
include_once 'Classes/ClassMessages.php';
include_once 'Classes/ClassConversation.php';
session_start();

$a = $_SESSION['login_id'];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['userId']) && isset($_GET['username'])) {
        $id = $_GET['userId'];

        $username = $_GET['username'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['newMessage'])) {

        $newMessage = $_POST['newMessage'];
        $idUser1 = $_SESSION['login_id'];
        $idUser2 = $_GET['userId'];

        $dateob = new DateTime("now");
        $date = $dateob->format('Y-m-d H:i:s');



        if(conversation::checkConversationId($conn, $idUser1, $idUser2)===null){
            $newConversation = new Conversation();



            $newConversation->setIdUser1($idUser1);

            $newConversation->setIdUser2($idUser2);
            $newConversation->saveToDB($conn);
        }

        $idConversation = conversation::checkConversationId($conn, $idUser1, $idUser2);

        if (strlen($newMessage) > 0) {
            $message = new Message();
            $message->setIdConversation($idConversation);
            $message->setMessage($newMessage);
            $message->setTime($date);
            $message->setIdUser1($idUser1);
            $message->saveToDB($conn);
            header('Location: sendMessage.php?userId=' . $idUser2);
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
Napisz nową wiadomość :

<form action="" method="post">
    <input type="textarea" name="newMessage">
    <button>Wyślij</button>
</form>

<?php
$idConversation = Conversation::checkConversationId($conn, $_SESSION['login_id'], $_GET['userId'] );
var_dump($idConversation);
$allMessages = Message::loadConversationMessages($conn, $idConversation);
var_dump($allMessages);
//foreach ($allMessages as $mes){
//    echo
//
//}
?>
</body>
</html>
