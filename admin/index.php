<?php
function dbConnect(){
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
        case 'user-list':
            require('./controllers/controller_user-list.php');
            break;

        case 'user-form':
            require('./controllers/controller_user-form.php');
            break;

        case 'article-list':
            require('./controllers/controller_article-list.php');
            break;

        case 'article-form':
            require('./controllers/controller_article-form.php');
            break;

        case 'category-list':
            require('./controllers/controller_category-list.php');
            break;

        case 'category-form':
            require('./controllers/controller_category-form.php');
            break;

        default:
            require('./controllers/controller_index.php');
            break;
    }
} else{
    require('./controllers/controller_index.php');
}