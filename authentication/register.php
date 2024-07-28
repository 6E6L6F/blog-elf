<?php 
require_once(__DIR__ . '/../template.php');
class Register{
    private $conn;
    private $template;
    public function __construct(Database $conn){
        $this->conn = $conn;
        $this->template = new FilePathCollector();


    }
    public function loadPage(){
        $this->template->getFilePathPhp("register");
    }

    public function registerUser(string $firstName , 
                string $lastName , 
                string $gmail , 
                string $phone , 
                string $userName ,
                string $password,
                string $profile,
                string $role,) : bool | string{
                    
         $result = $this->conn->registerUser(
                    firstName: $firstName,
                    lastName: $lastName,
                    username: $userName,
                    gmail: $gmail,
                    passwd: $password , 
                    rol: $role,
                    phone: $phone ,
                    profile: $profile,
                );
        if ($result == 1){
            return "you are registerd";
        }else {
            return $result;
        }
        
        
    }
}

?>