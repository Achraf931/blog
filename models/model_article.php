<?php

function getArticles($limit = false, $category = false){
    $db = dbConnect();

    $queryString = 'SELECT title, GROUP_CONCAT(name) as category_name, published_at, summary, a.id, a.image
 FROM article a JOIN article_category ac
 ON a.id = ac.article_id
 JOIN category c
 ON ac.category_id = c.id
 WHERE published_at <= NOW() AND is_published = 1 ';

    if ($category){
        $queryString .= ' AND ac.category_id = ' . $category;
    }

    if ($limit){
        $queryString .= ' GROUP BY a.id DESC
	ORDER BY published_at DESC LIMIT ' . $limit;
    }

    else{
        $queryString .= ' GROUP BY a.id DESC
	ORDER BY published_at DESC ';
    }

    $query = $db->query($queryString);
    return  $homeArticles=$query->fetchAll();

}
function getOneArticle($articleId){

    $db = dbConnect();

    $query = $db->prepare('SELECT title, GROUP_CONCAT(name) as name, published_at, summary, content, a.id, a.image
 FROM article a JOIN article_category ac
 ON a.id = ac.article_id
 JOIN category c
 ON ac.category_id = c.id
 WHERE a.id = ? AND published_at <= NOW() AND is_published = 1
GROUP BY a.id');

    $query->execute( array($articleId) );


    return $query->fetch();

}

function getCategories($category){

    $db = dbConnect();

    $selectedCategory = $db->prepare('SELECT name, description FROM category WHERE id = ?');

    $selectedCategory->execute(array($category));

    return $selectedCategory->fetch();
}