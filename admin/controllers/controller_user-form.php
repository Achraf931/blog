<?php
require_once('./models/model_user-form.php');
$message = newUser(isset($_POST['save']));
updateUser(isset($_POST['update']));
$user = recupUser(isset($_GET['user_id']) && isset($_GET['action']) && $_GET['action'] == 'edit');
require_once('./views/user-form.php');