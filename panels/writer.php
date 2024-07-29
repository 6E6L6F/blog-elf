<?php 
require_once(__DIR__ . '/../template.php');
class Writer extends FilePathCollector{
    private Database $conn;
    private Session $session;

    public function __construct(Database $conn , Session $session){
        $this->conn = $conn;
        $this->session = $session;
    }
    public function loadPage(){
        $this->getFilePathPhp("writer");
    }
    public function createPost(String $title ,
        String $desc ,
        String $abs ,
        String $photo ,
        String $date ,
        String $time ,
        Int $wid  ,
        Int $cid) : bool {
        $result = $this->conn->createPost(
                title: $title,
                desc: $desc,
                abs: $abs,
                date: $date,
                time: $time,
                photo: $photo,
                wid: $wid,
                cid: $cid
            );
        return $result;

    }
    public function CheckUser()
    {
        $userId = $this->session->get("session");
        $result = $this->conn->checkPermissionWriter(
            userId: $userId,
        );
        return $result;
    }
    public function createCategory(String $categoryName) : string{
        if ($this->CheckUser()){
            $result = $this->conn->createCategory(
                categoryName: $categoryName
            );
            if ($result) {
                return "Created";
            }
            return "can't create category";
        }
        return "error permission";
    }
    public function writeMedia(Array $mediaPath , Int $postId) : string{
        if ($this->CheckUser()){
    
            $result = $this->conn->writeMedia(
                postId: $postId,
                mediaPath: $mediaPath
            );
            if ($result) {
                return "media waited";
            }
            return "cant write media";
        }
        return "error permission";
    }
    public function getAllFeedBacks() : array | bool{
        if ($this->CheckUser()){
            $result = $this->conn->getAllFeedBacks();
            if ($result) {
                return $result;
            }
            return [];
        }
        return false;
    }
    public function getFeedBacksPost(Int $postId) : array | bool{
        if ($this->CheckUser()){
            $result = $this->conn->getFeedBacksPost(
                postId: $postId
            );
            if ($result) {
                return $result;
            }
            return [];
        }
        return false;
    }    

}

?>