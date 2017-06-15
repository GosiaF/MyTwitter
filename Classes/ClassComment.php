<?php
class Comment
{
    private $id;
    private $userId;
    private $postId;
    private $creationDate;
    private $text;

    /**
     * Comment constructor.
     * @param int $id
     * @param string $userId
     * @param string $postId
     * @param string $creationDate
     * @param string $text
     */
    public function __construct()
    {
        $this->id = -1;
        $this->userId = "";
        $this->postId = "";
        $this->creationDate = "";
        $this->text = "";
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @param string $postId
     */
    public function setPostId($postId)
    {
        $this->postId = $postId;
    }

    /**
     * @return string
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param string $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    static function loadCommentById($conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM comments WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->userId = $row['userId'];
            $loadedComment->postId = $row['postId'];
            $loadedComment->text = $row['text'];
            $loadedComment->creationDate = $row['creationDate'];
            return $loadedComment;
        }

        return null;
    }

    static public function loadAllCommentsByPostId($conn, $postId)
    {
        $stmt = $conn->prepare("SELECT * FROM comments WHERE postId=:postId ORDER BY id DESC ");
        $result = $stmt->execute(['postId' => $postId]);
        $ret = [];


        if ($result !== false && $stmt->rowCount() != 0) {
            foreach ($stmt as $row) {
//                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['userId'];
                $loadedComment->postId = $row['postId'];
                $loadedComment->text = $row['text'];
                $loadedComment->creationDate = $row['creationDate'];

                $ret[] = $loadedComment;
            }
        }
        return $ret;
    }

    public function saveToDB($conn)
    {
        if ($this->id == -1) {
            $stmt = $conn->prepare
            ('INSERT INTO comments(userId, postId, creationDate, text) VALUES (:userId, :postId, :creationDate, :text)');

            $result = $stmt->execute(
                ['userId' => $this->userId, 'postId' => $this->postId, 'creationDate' => $this->creationDate, 'text' => $this->text]
            );


            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else
        {
            $stmt = $conn->prepare(
                'UPDATE comments SET userId=:userId, postId=:postId, creationDate=:creationDate, text=:text WHERE id=:id'
            );
            $result = $stmt->execute(
                ['userId' => $this->userId, 'postId' => $this->postId,
                    'creationDate' => $this->creationDate, 'text' => $this->text, 'id' => $this->id]
            );
            if ($result === true) {
                return true;
            }
        }
        return false;
    }
}
//include_once '../DBconnect.php';
//$a = Comment::loadCommentById($conn, 1);
//var_dump($a);
//$test = Comment::loadAllCommentsByPostId($conn, 12);
//var_dump($test);

//$test = new Comment();
//$test->setUserId(3);
//$test->setPostId(3);
//$test->setText('nowy komentarz');
//$test->setCreationDate('2015-9-12');
//$test->saveToDB($conn);
//var_dump($test);