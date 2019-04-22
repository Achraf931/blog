<?php
require_once('./models/model_article.php');

$article = getOneArticle($_GET['article_id']);

require_once('./views/article.php');