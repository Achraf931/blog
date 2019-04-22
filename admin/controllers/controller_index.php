<?php
require_once('./models/model_index.php');

verifAdmin(!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0);

require_once('./views/index.php');
