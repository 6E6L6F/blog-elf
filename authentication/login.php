<?php 
require_once(__DIR__ . '/../template.php');

class Login {
    private $db;
    private $template;
    private $session;
    public function __construct(Database $conn , Session $session) {
        $this->template = new FilePathCollector();
        $this->db = $conn;
        $this->session = $session;
        
    }
    public function loadPage(){
        $this->template->getFilePathPhp("login");
    }
    public function login(string $username , string $passwd) : string{
        if ($this->session->isLoggedIn()){
            return "you was logined";
        }
        $user = $this->db->getPasswordByUsername($username);
        if($user && $this->db->hashing($passwd) == $user['passwd']){
            $this->session->login($username , $user['userid']);
            $this->session->set("userid" , $user['userid']);
            $this->session->setExpireTime(604800);
            return "you are logined";
        }else {
            return "username or password is not valid";
        }
    }
    
}

?>