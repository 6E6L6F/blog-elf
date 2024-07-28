<?php 
require_once(__DIR__ . '/../template.php');
class Profile extends FilePathCollector{
    private $conn;
    private $session;
    
    public function __construct(Session $session , Database $conn){
        $this->conn = $conn;
        $this->session = $session;

    }
    public function loadPage(){
        $this->getFilePathPhp("profile");
    }
    public function setupInfo(string $password = '', string $firstName = '', string $lastName = '', string $profile = '', string $gmail = ''){
        $user_id = $this->session->get("userid");
        $result = $this->conn->updateUser(
            userid: $user_id,
            firstName: $firstName,
            lastName: $lastName,
            profile: $profile,
            gmail: $gmail,
            password: $password
        );
        return $result;
    }
}


?>