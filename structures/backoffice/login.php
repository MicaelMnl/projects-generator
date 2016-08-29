<?php
define('NO_LOGIN_REQUIRED', true);
require 'conf/conf.php';
if(isset($_SESSION['user']['ID']) && !empty($_SESSION['user']['ID']))
        Utils::redirect('index.php');

$errors = new Errors();

if(isset($_POST['SUBMIT'])){
    if($_POST['EMAIL'] == ADMIN_ID && $_POST['PASSWORD'] == ADMIN_PASSWORD ){
        $_SESSION['user']['ID'] = ADMIN_ID ;
        Utils::redirect('index.php');
    }
           

}
echo $twig->render('login.twig',array(
    'errors' => $errors 
   ));
