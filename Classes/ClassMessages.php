<?php


class Message
{
    private $idMessage;
    private $idConversation;
    private $message;
    private $time;
    private $idUser1;
    private $idUser2;

    /**
     * ClassMessages constructor.
     * @param $idMessage
     * @param $idConversation
     * @param $message
     * @param $time
     */
    public function __construct()
    {
        $this->idMessage = -1;
        $this->idConversation = "";
        $this->message = "";
        $this->time = "";
    }

    /**
     * @return int
     */
    public function getIdMessage()
    {
        return $this->idMessage;
    }

    /**
     * @param int $idMessage
     */
    public function setIdMessage($idMessage)
    {
        $this->idMessage = $idMessage;
    }


    public function getIdConversation()
    {
        return $this->idConversation;
    }

    /**
     * @param string $idConversation
     */
    public function setIdConversation($idConversation)
    {
        $this->idConversation = $idConversation;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getIdUser1()
    {
        return $this->idUser1;
    }

    /**
     * @param mixed $idUser1
     */
    public function setIdUser1($idUser1)
    {
        $this->idUser1 = $idUser1;
    }

    /**
     * @return mixed
     */
    public function getIdUser2()
    {
        return $this->idUser2;
    }

    /**
     * @param mixed $idUser2
     */
    public function setIdUser2($idUser2)
    {
        $this->idUser2 = $idUser2;
    }


    static function loadMessageById($conn, $idMessage)
    {
        $stmt = $conn->prepare('SELECT * FROM messages WHERE id=:idMessage');
        $result = $stmt->execute(['id' => $idMessage]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedMessage = new Message();
            $loadedMessage->idMessage = $row['idMessage'];
            $loadedMessage->idConversation = $row['idConversation'];
            $loadedMessage->message = $row['message'];
            $loadedMessage->time = $row['datatime'];
            return $loadedMessage;
        }
        return null;
    }


    public function saveToDB($conn)
    {
        if ($this->idMessage == -1) {
            $stmt = $conn->prepare
            ('INSERT INTO messages(idConversation, message, datatime, idUser1) VALUES (:idConversation, :message, :datatime, :idUser1)');

            $result = $stmt->execute(
                ['idConversation' => $this->idConversation, 'message' => $this->message, 'datatime' => $this->time, 'idUser1' => $this->idUser1]
            );


            if ($result !== false) {
                $this->idMessage = $conn->lastInsertId();
                return true;
            }
        } else {
            $stmt = $conn->prepare(
                'UPDATE messages SET idConversation=:idConversation, message=:message, datatime=:datatime WHERE idMessage=:idMessage'
            );
            $result = $stmt->execute(
                ['idConversation' => $this->idConversation, 'message' => $this->message,
                    'time' => $this->time,'idMessage' => $this->idMessage]
            );
            if ($result === true) {
                return true;
            }
        }
        return false;
    }
    static function loadConversationMessages($conn, $idConversation)
    {
        $ret = [];
        $stmt = $conn->prepare('SELECT * FROM messages JOIN conversation ON messages.idConversation=conversation.id_conversation WHERE conversation.id_conversation=:idConversation;');
        $stmt->execute(['idConversation' => $idConversation]);
        $result = $stmt->fetchAll();

        if ($result !== false && count($result) != 0) {
            foreach ($result as $mNr => $row) {
                $loadedMessage = new Message();
                $loadedMessage->idMessage = $row['idMessage'];
                $loadedMessage->idConversation = $row['idConversation'];
                $loadedMessage->message = $row['message'];
                $loadedMessage->time = $row['datatime'];
                $loadedMessage->idUser1 = $row['idUser1'];
                $loadedMessage->idUser2 = $row['idUser2'];
                $ret[] = $loadedMessage;
            }
        }
        return $ret;
    }
}






