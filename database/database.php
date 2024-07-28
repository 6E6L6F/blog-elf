<?php
class Database {
    private $dsn;
    private $username;
    private $password;
    private $conn;

    public function __construct($dsn, $username, $password ) {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect() : bool | PDO{
        try {
            $this->conn = new PDO($this->dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            echo 'Connection failed: '. $e->getMessage();
            return false;
        }
    }
    public function getPasswordByUsername(string $userName) : bool | array {
        if ($this->connect()) {
            $stmt = $this->conn->prepare('SELECT passwd,userid FROM users WHERE username = :username');
            $stmt->bindParam(':username', $userName);
            $stmt->execute();
            $result = $stmt->fetch();
            if ($result) {
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function registerUser(string $phone,
        string $firstName,
        string $lastName,
        string $profile,
        string $userName,
        string $passwd,
        string $gmail,
        string $rol) : bool | string {
        if ($this->connect()) {
            $stmt = $this->conn->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->bindParam(':username', $userName);
            $stmt->execute();
            $result = $stmt->fetch();
            if ($result) {
                return "username not valid"; 
             }
    
            if (strlen($passwd) < 8) {
                return "password len < 8"; 
            }
    
            $stmt = $this->conn->prepare('INSERT INTO users (first_name, last_name, profile_, phone , username, passwd, gmail, rol) VALUES (:firstName, :lastName, :profile, :phone, :username, :passwd, :gmail, :rol)');
            $password = $this->hashing($passwd);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':profile', $profile);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':username', $userName);
            $stmt->bindParam(':passwd', $password);
            $stmt->bindParam(':gmail', $gmail);
            $stmt->bindParam(':rol', $rol);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function getAllBlogs() : bool | array{
        if ($this->connect()) {
            $stmt = $this->conn->prepare('SELECT * FROM blogs LIMIT 10');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            return false;
        }
    }
    public function getCategoryList() : bool | array{
        if ($this->connect()) {
            $stmt = $this->conn->prepare('SELECT * FROM category');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            return false;
        }
    }
    public function getPostsByCategoryId(int $cid) : bool | array{
        if ($this->connect()) {
            $stmt = $this->conn->prepare('SELECT * FROM blogs WHERE cid = :cid');
            $stmt->bindParam(':cid', $cid);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            return false;
        }
    }
    public function getBestSeenBlogs() : bool | array {
        if ($this->connect()) {
            $stmt = $this->conn->prepare('SELECT * FROM blogs WHERE seen > 10');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            return false;
        }
    }
    public function getBlogsBestLikes() : bool | array{
        if ($this->connect()) {
            $stmt = $this->conn->prepare('
                SELECT b.*
                FROM blogs b
                JOIN (
                    SELECT bid, COUNT(like_) as like_count
                    FROM feedback
                    WHERE like_ = 1
                    GROUP BY bid
                    HAVING COUNT(like_) > 1
                ) f ON b.bid = f.bid
            ');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            return false;
        }
    }
    public function updateUser(int $userid, string $password = '',
        string $firstName = '',
        string $lastName = '',
        string $profile = '',
        string $gmail = '') : bool{

        if ($this->connect()) {
            $stmt = $this->conn->prepare('UPDATE users SET ');
            $updates = array();
            if (!empty($password)) {
                $updates[] = 'passwd = :password';
                $stmt->bindParam(':password', $password);
            }

            if (!empty($firstName)) {
                $updates[] = 'first_name = :firstName';
                $stmt->bindParam(':firstName', $firstName);
            }
            if (!empty($lastName)) {
                $updates[] = 'last_name = :lastName';
                $stmt->bindParam(':lastName', $lastName);
            }
            if (!empty($profile)) {
                $updates[] = 'profile_ = :profile';
                $stmt->bindParam(':profile', $profile);
            }
            if (!empty($gmail)) {
                $updates[] = 'gmail = :gmail';
                $stmt->bindParam(':gmail', $gmail);
            }

            $stmt = $this->conn->prepare('UPDATE users SET ' . implode(', ', $updates) . ' WHERE userid = :userid');
            $stmt->execute();
            $stmt->bindParam(':userid', $userid);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function checkPermissionAdmin(string $username) : bool{
        if ($this->connect()) {
            $stmt = $this->conn->prepare('SELECT rol FROM users WHERE username = :username');
            $stmt->execute(array(':username' => $username));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result['rol'] != "admin") {
                return False;
            }
            return True;
        }
        return False;
    }
    public function createPost(
        string $title,
        string $desc,
        string $abs,
        string $date,
        string $time,
        String $photo,
        int $wid,
        int $cid) : bool {
        if ($this->connect()) {
            $stmt = $this->conn->prepare('
                INSERT INTO blogs (title, description, abstract,photo, date, time, wid, cid)
                VALUES (:title, :desc, :abs, :photo, :date, :time, :wid, :cid)
            ');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':desc', $desc);
            $stmt->bindParam(':abs', $abs);
            $stmt->bindParam(':photo', $photo);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':time', $time);
            $stmt->bindParam(':wid', $wid);
            $stmt->bindParam(':cid', $cid);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function writeMedia(Int $postId, array $mediaPath) : bool {
        if ($this->connect()) {
            $stmt = $this->conn->prepare('
                INSERT INTO media (bid, path_file)
                VALUES (:postId, :mediaPath)
            ');
            foreach ($mediaPath as $path) {
                $stmt->bindParam(':postId', $postId);
                $stmt->bindParam(':mediaPath', $path);
                if (!$stmt->execute()) {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }
    public function createCategory(string $categoryName) : bool {
        if ($this->connect()) {
            $stmt = $this->conn->prepare('INSERT INTO category (category_name) VALUES (:categoryName)');
            $stmt->bindParam(':categoryName', $categoryName);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function getPostIdAndTitle() : array | false{
        if ($this->connect()) {
            $stmt = $this->conn->prepare('
                SELECT bid, title
                FROM blogs
            ');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            return false;
        }
    }

    public function getPost(Int $postId) : false | array {
        if ($this->connect()) {
            $stmt = $this->conn->prepare('
                SELECT b.*, u.first_name, u.last_name, u.profile_
                FROM blogs b
                JOIN users u ON b.wid = u.userid
                WHERE b.bid = :postId
            ');
            $stmt->bindParam(':postId', $postId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $stmt = $this->conn->prepare('
                    SELECT path_file
                    FROM media
                    WHERE bid = :postId
                ');
                $stmt->bindParam(':postId', $postId);
                $stmt->execute();
                $mediaFiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $result['media_files'] = $mediaFiles;
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function getAllUserName() : array {
        if ($this->connect()) {
            $stmt = $this->conn->prepare('SELECT username FROM users');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            return [];
        }
    }

    public function getAllWriter() : array {
        if ($this->connect()) {
            $stmt = $this->conn->prepare('SELECT * FROM users WHERE rol = "writer"');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            return [];
        }
    }

    public function getAllUser() : array {
        if ($this->connect()) {
            $stmt = $this->conn->prepare('SELECT * FROM users');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            return [];
        }
    }
    public function hashing(string $data) : string {
        return hash('sha384', $data);
    }


}