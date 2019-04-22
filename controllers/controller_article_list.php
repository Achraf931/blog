<?php
require_once('./models/model_article.php');

$articles = getArticles(null, null);
if (isset($_GET['category_id'])) {
    $articles = getArticles(null, $_GET['category_id']);
    $selectedCategory = getCategories($_GET['category_id']);
}
require_once('./views/article_list.php');