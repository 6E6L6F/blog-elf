<?php 
require_once(__DIR__ . '/../template.php');
class Writer {
    private Database $conn;
    private Session $session;

    public function __construct(Database $conn , Session $session){
        $this->conn = $conn;
        $this->session = $session;
    }
    public function CreatePost(String $title ,
        String $desc ,
        String $abs ,
        String $photo ,
        String $date ,
        String $time ,
        Int $wid  ,
        Int $cid) {
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
}


?>