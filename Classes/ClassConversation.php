<?php

class Conversation
{

    private $idConversation;
    //private $id_message;
    private $idUser1;
    private $idUser2;

    /**
     * Conversation constructor.
     * @param $id_conversation
     * @param $id_user1
     * @param $id_user2
     */
    public function __construct()
    {
        $this->idConversation = -1;

    }

    /**
     * @return int
     */
    public function getIdConversation()
    {
        return $this->idConversation;
    }


    /**
     * @return string
     */
    public function getIdUser1()
    {
        return $this->idUser1;
    }

    /**
     * @param string $id_user1
     */
    public function setIdUser1($idUser1)
    {
        $this->idUser1 = $idUser1;
    }

    /**
     * @return string
     */
    public function getIdUser2()
    {
        return $this->idUser2;
    }

    /**
     * @param string $id_user2
     */
    public function setIdUser2($idUser2)
    {
        $this->idUser2 = $idUser2;
    }



    public function saveToDB($conn) {
        if ($this->idConversation == -1) {
            //Saving new tweet to database

            $stmt = $conn->prepare('INSERT INTO conversation(user1, user2) VALUES (:user1, :user2)');

            $result = $stmt->execute([

                'user1' => $this->idUser1,
                'user2' => $this->idUser2,
            ]);

            if ($result !== false) {
                $this->idConversation = $conn->lastInsertId();
                return true;
            }
        }
        return false;
    }



    static public function checkConversationId($conn, $idUser1, $idUser2) {

        $stmt = $conn->prepare('SELECT * FROM Conversation WHERE (user1=:user1 AND user2=:user2) OR (user1=:user2 AND user2=:user1);');
        $result = $stmt->execute([
            'user1' => $idUser1,
            'user2' => $idUser2,
        ]);

        if ($result !== false && count($result) != 0) {
            $row = $stmt->fetch();

            $idConversation = $row['id_conversation'];

            return $idConversation;
        }
        return null;
    }

}