<?php
require_once(__DIR__ . '/../template.php');
class Admin extends FilePathCollector{
    private Session $session;
    private Database $conn;

    public function __construct(Session $session , Database $conn){
        $this->session = $session;
        $this->conn = $conn;

    }
    public function loadPage(){
        if ($this->conn->CheckPermissionAdmin($this->session->get("username"))) {
            $this->getFilePathPhp("admin");
        }else {
            $this->getFilePathPhp("not_found");
        }
    }

    public function deleteUser(int $userId) : bool{
        return true;
    }
    public function deleteWriter(int $writerId) : bool{
        return true;
    }
    public function deletePost(int $postId) : bool {
        return true;

    }
    public function addWriter(string $first_name ,
        string $last_name ,
        string $gmail ,
        string $phone ,
        string $username ,
        string $password ,
        string $profile,
        string $role,) : bool {

        return True;
    }
    public function upgradeToWriter(int $username) : bool {
        return true;

    }
}

?>