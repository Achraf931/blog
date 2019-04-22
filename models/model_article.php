<?php


if (isset($_GET['logout']) && isset($_SESSION['user'])) {
    //la fonction unset() détruit une variable ou une partie de tableau. ici on détruit la session user
    unset($_SESSION["user"]);
}

function getArticles($limit = false, $category = false){
    $db = dbConnect();

    $queryString = 'SELECT title, GROUP_CONCAT(name) as category_name, published_at, summary, article.id, article.image
 FROM article INNER JOIN article_category
 ON article.id = article_category.article_id
 
 INNER JOIN category
 ON article_category.category_id = category.id
 WHERE published_at <= NOW() AND is_published = 1
     ';

    if ($category){
        $queryString .= ' AND article_category.category_id = ' . $category;
    }

    if ($limit){
        $queryString .= ' GROUP BY article.id DESC
	ORDER BY published_at DESC LIMIT ' . $limit;
    }

    else{
        $queryString .= ' GROUP BY article.id DESC
	ORDER BY published_at DESC ';
    }

    $query = $db->query($queryString);
    return  $homeArticles=$query->fetchAll();

}
function getOneArticle($articleId){

    $db = dbConnect();

    $query = $db->prepare('SELECT title, GROUP_CONCAT(name) as name, published_at, summary, content, article.id, article.image
 FROM article INNER JOIN article_category
 ON article.id = article_category.article_id
 
 INNER JOIN category
 ON article_category.category_id = category.id
 WHERE article.id = ? AND published_at <= NOW() AND is_published = 1
     GROUP BY article.id');

    $query->execute( array($articleId) );


    return $query->fetch();

}

function getCategories($category){

    $db = dbConnect();

    $selectedCategory = $db->prepare('SELECT name, description FROM category WHERE id = ?');

    $selectedCategory->execute(array($category));

    return $selectedCategory->fetch();
}