<?php

function dbConnect(){

    setlocale(LC_ALL, "fr_FR");

    try{
        return $db = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exception $exception)
    {
        die( 'Erreur : ' . $exception->getMessage() );
    }
}
$db = dbConnect();
session_start();

if (isset($_GET['page'])){
    switch ($_GET['page']){
        case 'article_list':
            require('./controllers/controller_article_list.php');
            break;

        case 'article':
            require('./controllers/controller_article.php');
            break;

        case 'login-register':
            require('./controllers/controller_login-register.php');
            break;

        case 'user-profile':
            require('./controllers/controller_user-profile.php');
            break;

        default:
            require('./controllers/controller_index.php');
            break;
    }
} else{
    require('./controllers/controller_index.php');
}