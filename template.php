<?php

class FilePathCollector {

    public function getFilePathPhp(string $fileName){
        require 'template/php/'. $fileName . ".template.php";
    }
}





