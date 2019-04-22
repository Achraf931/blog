<?php
require_once('./models/model_article.php');

$homeArticles = getArticles(3, null);

require_once('./views/index.php');