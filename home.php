<?php 
session_start();
require "template.php";

class Index extends FilePathCollector {
    private $conn;
    private $session;

    public function __construct(Database $conn , Session $session) {
        $this->conn = $conn;
        $this->session = $session;
    }
    public function index(array $data){
        $this->getFilePathPhp("header");
        foreach($data as $blog) {
            ?> 
            <?=$blog['bid']?> <br>
            <?=$blog['abstract']?><br>
            <?=$blog['long_description']?><br>
            <?=$blog['title']?><br>
            <?=$blog['photo']?><br>
            <?=$blog['date_time']?><br>
            <?=$blog['wid']?><br>
            <?=$blog['cid']?><br>
            <?=$blog['seen']?><br>
        <?php
        }    
        $this->getFilePathPhp("footer");
    }

    public function search(string $text){
        $this->index(["search : " . $text]);
    }

    public function category(int $id){
        $result = $this->conn->getPostsByCategoryId($id);
        $this->index($result);
    }

    public function showPosts() {
        $getAllBlogs = $this->conn->getAllBlogs();
        $getCategoryList = $this->conn->getCategoryList();
        $getBestSeenBlogs = $this->conn->getBestSeenBlogs();
        $getBlogsBestLikes = $this->conn->getBlogsBestLikes();
        $this->getFilePathPhp("header");
        foreach($getAllBlogs as $blog) {
            ?> 
            <div>
                all posts
                <?=$blog['bid']?> <br>
                <?=$blog['abstract']?><br>
                <?=$blog['long_description']?><br>
                <?=$blog['title']?><br>
                <?=$blog['photo']?><br>
                <?=$blog['date_time']?><br>
                <?=$blog['wid']?><br>
                <?=$blog['cid']?><br>
                <?=$blog['seen']?><br>
            </div>
        <?php
        }    

        foreach($getCategoryList as $blog) {
            ?> 
            <div>
                category

                <?=$blog['cid']?> <br>
                <?=$blog['c_name']?> <br>

            </div>
        <?php
        }  

        foreach($getBestSeenBlogs as $blog) {
            ?> 
            <div>
                best seen
                <?=$blog['bid']?> <br>
                <?=$blog['abstract']?><br>
                <?=$blog['long_description']?><br>
                <?=$blog['title']?><br>
                <?=$blog['photo']?><br>
                <?=$blog['date_time']?><br>
                <?=$blog['wid']?><br>
                <?=$blog['cid']?><br>
                <?=$blog['seen']?><br>
            </div>
        <?php
        }  

        foreach($getBlogsBestLikes as $blog) {
            ?> 
            best like
            <div>
                <?=$blog['bid']?> <br>
                <?=$blog['abstract']?><br>
                <?=$blog['long_description']?><br>
                <?=$blog['title']?><br>
                <?=$blog['photo']?><br>
                <?=$blog['date_time']?><br>
                <?=$blog['wid']?><br>
                <?=$blog['cid']?><br>
                <?=$blog['seen']?><br>
            </div>
        <?php
        }  

        $this->getFilePathPhp("footer");

        
    }
}

?>