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
        <?php require('./controllers/controller_nav.php'); ?>
        <main class="col-9">
            <section class="all_articles">
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
                                        <a href="index.php?page=article&article_id=<?= $article['id']; ?>">> Lire l'article</a>
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
