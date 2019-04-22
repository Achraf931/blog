<?php
require_once('./models/model_user-list.php');
getUserList(isset($_GET['user_id']) && isset($_GET['action']) && $_GET['action'] == 'delete');
$users = getUserList(isset($_GET['user_id']));
require_once('./views/user-list.php');