<?php
require 'conf/conf.php';

if(isset($_GET['action']) &&  $_GET['action'] == 'logOut'){
    $_SESSION['user']['ID']  = array();
    Utils::redirect('login.html');
}
echo $twig->render('index.twig',array(
   ));
