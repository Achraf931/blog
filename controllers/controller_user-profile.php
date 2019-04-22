<?php
if(!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 1){
    header('location:index.php');
    exit;
}

require_once('./models/model_user-profile.php');

$message = updateUser(isset($_POST['update']));
$user = recupInfo();
require_once('./views/user-profile.php');