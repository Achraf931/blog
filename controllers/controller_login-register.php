<?php
require_once('./models/model_login-register.php');

$loginError = loginFunc(isset($_POST['login']));

$registerError = registerFunc(isset($_POST['register']));
require_once('./views/login-register.php');