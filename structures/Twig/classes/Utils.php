<?php
class Utils {
    // tutes le function en statique 
	public static function upload($file,$chemin){
       $uploaddir = $chemin;
       $uploadfile = $uploaddir . basename($file['name']);
       move_uploaded_file($file['tmp_name'], $uploadfile);
       return $file['name'];
    }
    public static function redirect($url){
        header('Location: '.PATH.$url);
        exit();
    }
}