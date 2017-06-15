<?php
Class Tweet
{
 private $id;
 private $userId;
 private $text;
 private $creationDate;

    public function __construct()
    {
        $this->id = -1;
        $this->userId = "";

        $this->text = "";
        $this->creationDate = "";
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param mixed $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    static function loadTweetById($conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM tweets WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['userId'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creationDate'];
            return $loadedTweet;
        }

        return null;
    }

    static public function loadAllTweetsByUserId($conn, $userId)
    {
        $stmt = $conn->prepare("SELECT * FROM tweets WHERE userId=:userId");
        $result = $stmt->execute(['userId' => $userId]);
        $ret = [];


        if ($result !== false && $stmt->rowCount() != 0) {
              foreach ($stmt as $row) {
//                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['userId'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creationDate'];

                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }

    static public function loadAllTweets($conn)
    {
        $sql = "SELECT * FROM tweets ORDER BY id DESC";
        $ret = [];
        $result = $conn->query($sql);

        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['userId'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creationDate'];

                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }

    public function saveToDB($conn)
    {
        if ($this->id == -1) {
            $stmt = $conn->prepare
            ('INSERT INTO tweets(userId, text, creationDate) VALUES (:userId, :text, :creationDate)');

            $result = $stmt->execute(
                ['userId' => $this->userId, 'text' => $this->text, 'creationDate' => $this->creationDate]
            );

            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else
        {
            $stmt = $conn->prepare(
                'UPDATE tweets SET userId=:userId, text=:text, creationDate=:creationDate WHERE id=:id'
            );
            $result = $stmt->execute(
                ['userId' => $this->userId, 'text' => $this->text,
                    'creationDate' => $this->creationDate, 'id' => $this->id]
            );
            if ($result === true) {
                return true;
            }
        }

        return false;
    }


}
//include_once '../DBconnect.php';

//$test = Tweet::loadTweetById($conn, 6);
//var_dump($test);

//$test2 = Tweet::loadAllTweetsByUserId($conn, 5);
//var_dump($test2);

//$test3 = Tweet::loadAllTweets($conn);
//var_dump($test3);

//$test = new Tweet();
//$test->setUserId(3);
//$test->setText('nowy tweet');
//$test->setCreationDate('2015-9-12');
//$test->saveToDB($conn);
//var_dump($test);