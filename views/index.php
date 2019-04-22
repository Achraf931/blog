<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Homepage - Mon premier blog !</title>
    <?php require 'partials/head_assets.php'; ?>
</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require './controllers/controller_nav.php'; ?>

        <main class="col-9">
            <section class="latest_articles">
                <header class="mb-4"><h1>Les 3 derniers articles :</h1></header>

                <!-- les trois derniers articles -->

                <?php foreach ($homeArticles as $key => $article): ?>
                    <article class="mb-4">
                        <h2><?php echo $article['title']; ?></h2>
                        <div class="row">
                            <?php if (isset($article['image']) AND !empty($article['image'])): ?>
                                <div class="col-12 col-md-4 col-lg-3">
                                    <img class="img-fluid" src="img/article/<?= $article['image']; ?>" alt="">
                                </div>
                            <?php endif; ?>
                            <div class="col-12 col-md-8 col-lg-9">
                                <strong class="article-category">[<?= $article['category_name']; ?>]</strong>
                                <span class="article-date">
								<!-- affichage de la date de l'article selon le format %A %e %B %Y -->
								<?php echo strftime("%A %e %B %Y", strtotime($article['published_at'])); ?>
							    </span>
                                <div class="article-content">
                                    <?php echo $article['summary']; ?>
                                </div>
                                <a href="index.php?page=article&article_id=<?= $article['id']; ?>">> Lire l'article</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
            <div class="text-right">
                <a href="index.php?page=article_list">> Tous les articles</a>
            </div>
        </main>
    </div>

    <?php require 'partials/footer.php'; ?>

</div>
</body>
</html>
