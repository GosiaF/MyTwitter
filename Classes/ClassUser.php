<?php


class User
{
    private $id;
    private $username;
    private $hashPass;
    private $email;


    public function __construct()
    {
        $this->id = -1;
        $this->username = "";
        $this->email = "";
        $this->hashPass = "";
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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getHashPass()
    {
//
//        $newHashedPassword = password_hash($newPass, PASSWORD_BCRYPT);
//        $this->hashPass = $newHashedPassword;

        return $this->hashPass;

    }

    /**
     * @param string $hashPass
     */
    public function setHashPass($hashPass)
    {
        $this->hashPass = $hashPass;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


    public function saveToDB($conn)
    {
        if ($this->id == -1) {
            $stmt = $conn->prepare
            ('INSERT INTO users(username, email, hash_pass) VALUES (:username, :email, :pass)');

            $result = $stmt->execute(
                ['username' => $this->username, 'email' => $this->email, 'pass' => $this->hashPass]
            );


            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else
            {
                $stmt = $conn->prepare(
                    'UPDATE Users SET username=:username, email=:email, hash_pass=:hash_pass WHERE id=:id'
                );
                $result = $stmt->execute(
                    ['username' => $this->username, 'email' => $this->email,
                        'hash_pass' => $this->hashPass, 'id' => $this->id]
                );
                if ($result === true) {
                    return true;
                }
            }

        return false;


    }


    static public function loadUserById($conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }

        return null;
    }

    static public function loadUserByUsername($conn, $username)
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE username=:username');
        $result = $stmt->execute(['username' => $username]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUsername = new User();
            $loadedUsername->id = $row['id'];
            $loadedUsername->username = $row['username'];
            $loadedUsername->hashPass = $row['hash_pass'];
            $loadedUsername->email = $row['email'];
            return $loadedUsername;
        }

        return null;
    }

    static public function loadUserByMail($conn, $email)
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE email=:email');
        $result = $stmt->execute(['email' => $email]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }
///////////////////
    static public function loadUserByPass($conn, $hashpass)
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE hash_pass=:hashpass');
        $result = $stmt->execute(['hash_pass' => $hashpass]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }
/////////////////////

    static public function loadAllUsers($conn)
    {
        $sql = "SELECT * FROM Users";
        $ret = [];
        $result = $conn->query($sql);

        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashPass = $row['hash_pass'];
                $loadedUser->email = $row['email'];

                $ret[] = $loadedUser;
            }
        }
        return $ret;
    }

    public function delete($conn)
    {
        if ($this->id != -1) {
            $stmt = $conn->prepare('DELETE FROM Users WHERE id=:id');
            $result = $stmt->execute(['id' => $this->id]);
            if ($result === true) {
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }


}

//include_once '../DBconnect.php';

//$test = new User();
//$test->setEmail('d@3.pl');
//$test->setUsername('ino');
//$test->setHashPass('bl3e');
//$test->saveToDB($conn);
//var_dump($test);

//$a = User::loadUserById($conn, 1);
//$a->delete($conn);
//
//var_dump($a);
