<?php

require_once 'tools/common.php';

//si une catégorie est demandée
if (isset($_GET['category_id'])) {
    //si l'id envoyé n'est pas un nombre entier, je redirige
    if (!ctype_digit($_GET['category_id'])) {
        header('location:index.php');
        exit;
    }
    //selection des articles de la catégorie demandée
    $queryArticles = $db->prepare('SELECT a.* , GROUP_CONCAT(c.name) as category_name
		FROM article a JOIN article_category ac
		ON a.id = ac.article_id
		JOIN category c ON ac.category_id = c.id
		WHERE ac.category_id = ? AND a.published_at <= NOW() AND a.is_published = 1
		GROUP BY a.id DESC
		ORDER BY a.published_at DESC');

    $queryArticles->execute(array($_GET['category_id']));

    //selection des informations de la catégorie demandée
    $queryCategory = $db->prepare('SELECT * FROM category WHERE id = ?');
    $queryCategory->execute(array($_GET['category_id']));
    $selectedCategory = $queryCategory->fetch();

    //si la catégorie n'a pas été trouvé je redirige
    if ($selectedCategory == false) {
        header('location:index.php');
        exit;
    }
} else {
    //si pas de catégorie demandée j'affiche tous les articles
    $queryArticles = $db->query('SELECT a.* , GROUP_CONCAT(c.name) as category_name
	FROM article a JOIN article_category ac
	ON a.id = ac.article_id
	JOIN category c ON ac.category_id = c.id
	WHERE a.published_at <= NOW() AND a.is_published = 1
	GROUP BY a.id DESC
	ORDER BY a.published_at DESC');
}
//puis je récupère les données selon la requête générée avant
$articles = $queryArticles->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php if (isset($_GET['category_id'])): ?><?= $selectedCategory['name']; ?><?php else: ?>Tous les articles<?php endif; ?>
        - Mon premier blog !</title>
    <?php require 'partials/head_assets.php'; ?>
</head>
<body class="article-list-body">
<div class="container-fluid">
    <?php require 'partials/header.php'; ?>
    <div class="row my-3 article-list-content">
        <?php require('partials/nav.php'); ?>
        <main class="col-9">
            <section class="all_aricles">
                <header>
                    <?php if (isset($_GET['category_id'])): ?>
                        <h1 class="mb-4"><?= $selectedCategory['name']; ?></h1>
                        <?php if (isset($selectedCategory['image']) AND !empty($selectedCategory['image'])): ?>
                            <img class="pb-4 img-fluid" src="img/category/<?php echo $selectedCategory['image']; ?>" alt="">
                        <?php endif; ?>
                    <?php else: ?>
                        <h1 class="mb-4">Tous les articles :</h1>
                    <?php endif; ?>
                </header>
                <?php if (isset($_GET['category_id'])): ?>
                    <div class="category-description mb-4">
                        <strong><?= $selectedCategory['description']; ?></strong>
                    </div>
                <?php endif; ?>
                <!-- s'il y a des articles à afficher -->
                <?php if (count($articles) > 0): ?>
                    <?php foreach ($articles as $key => $article): ?>
                            <article class="mb-4">
                                <h2><?= $article['title']; ?></h2>
                                <div class="row">
                                    <?php if (isset($article['image']) AND !empty($article['image'])): ?>
                                        <div class="col-12 col-md-4 col-lg-3">
                                            <img class="pb-4 img-fluid" src="img/article/<?= $article['image']; ?>" alt="">
                                        </div>
                                    <?php endif; ?>
                                    <div class="col-12 col-md-8 col-lg-9">
                                        <?php if (!isset($_GET['category_id'])): ?>
                                            <strong class="article-category">[<?= $article['category_name']; ?>]</strong>
                                        <?php endif; ?>
                                        <span class="article-date">
									<!-- affichage de la date de l'article selon le format %A %e %B %Y -->
									<?= strftime("%A %e %B %Y", strtotime($article['published_at'])); ?>
								        </span>
                                        <div class="article-content">
                                            <?= $article['summary']; ?>
                                        </div>
                                        <a href="article.php?article_id=<?= $article['id']; ?>">> Lire l'article</a>
                                    </div>
                                </div>
                            </article>
                    <?php endforeach; ?>
                    <!-- s'il n'y a pas d'articles à afficher -->
                <?php else: ?>
                    Aucun article à afficher...
                <?php endif; ?>
            </section>
        </main>
    </div>
    <?php require 'partials/footer.php'; ?>
</div>

</body>
</html>
